<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\RegistrationFormType;
use App\Security\ClientAuthenticationAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;


class RegistrationController extends AbstractController
{

    /**
     * @Route("/register", name="registre")
     */
    public function register(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, GuardAuthenticatorHandler $guardHandler, ClientAuthenticationAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new Client();
        $user->setDateCurent(new \DateTime('now'));
        $user->setPoints(0);
        $user->setStatus(true);
        $user->setRoles(['ROLE_USER']);


        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('brochure')->getData();
            $newFilename = 'avatar.jpg';
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $newFilename = 'null';
                }
            }
            $user->setBrochureFilename($newFilename);
            // encode the plain password
            $user->setPassword(
            $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
            return $this->redirectToRoute("/");
        }

        return $this->render('registration/register.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    /////MOBILE SERVICES/////
    /**
     * @Route("mobile/register", name="mobile_register" , methods={"POST"})
     */
    public function registerMobile(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, GuardAuthenticatorHandler $guardHandler, ClientAuthenticationAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $email = $request->get("email");
        $nom = $request->get("nom");
        $prenom = $request->get("prenom");
        $tel = $request->get("tel");
        $pwd = $request->get('plainPassword');
        if($email && $nom && $prenom && $tel && $pwd) {
            $user = new Client();
            $user->setEmail($email);
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setDateCurent(new \DateTime('now'));
            $user->setPoints(0);
            $user->setStatus(true);
            $user->setRoles(['ROLE_USER']);
            $user->setNumTel($tel);
            $newFilename = 'avatar.jpg';
            $user->setBrochureFilename($newFilename);
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $pwd
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->json(array('error' => false));
        }
        return $this->json(array('error' => true));
    }


}
