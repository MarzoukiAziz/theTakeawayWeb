<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\MenuElementRepository;
use App\Security\ClientAuthenticationAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    ////MOBILE SERVICE//////
    /**
     * @Route("/mobile/login", name="mobile_login", methods={"GET"})
     */
    public function mobileLogin(Request $request,UserPasswordEncoderInterface $userPasswordEncoder): Response
    {

        try {
            $email = $request->get("email");
            $pwd = $request->get('pwd');
            $u = $this->getDoctrine()->getRepository(Client::class)->findOneBy(['email' => $email]);

            if (!$u) {
                return $this->json(array('error' => true));
            }
            if($userPasswordEncoder->isPasswordValid($u,$pwd)) {
                $data = array(
                    'id' => $u->getId(),
                    'nom'=>$u->getNom(),
                    'prenom'=>$u->getPrenom(),
                    'role'=>$u->getRoles(),
                    'email'=>$u->getEmail(),
                    'num_tel'=>$u->getNumTel(),
                    'points'=>$u->getPoints(),
                    'avatar'=>$u->getBrochureFilename()
                );
                return $this->json(array('error' => false, "user" => $data));
            }else{
                return $this->json(array('error' => true));
            }

        } catch (Exception $e) {
            return $this->json(array('error' => true));
        }
    }
    /**
     * @Route("/mobile/user/edit/", name="usezr_edit_mobile", methods={"POST"})
     */
    public function MobileEditUser(Request $request): Response
    {
        try {
            $id = $request->get("id");
            $nom = $request->get('nom');
            $prenom = $request->get('prenom');
            $email = $request->get("email");
            $tel = $request->get("num_tel");
            $rep=$this->getDoctrine()->getRepository(Client::class);
            $u = $rep->find($id);
            $u->setNom($nom);
            $u->setPrenom($prenom);
            $u->setEmail($email);
            $u->setNumTel($tel);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->json(array('error' => false));
        } catch (Exception $e) {
            print($e);
            return $this->json(array('error' => true));
        }
    }
    /**
     * @Route("/mobile/users/", name="mobile_users", methods={"GET"})
     */
    public function mobileUsers(Request $request)
    {
        try {
            $users = $this->getDoctrine()->getRepository(Client::class)->findAll();

            $res = array();

            for ($i = 0; $i < sizeof($users); $i++) {
                $u = $users[$i];
                $data = array(
                    'id' => $u->getId(),
                    'nom' => $u->getNom(),
                    'prenom' => $u->getPrenom(),
                    'role' => $u->getRoles(),
                    'email' => $u->getEmail(),
                    'num_tel' => $u->getNumTel(),
                    'points' => $u->getPoints(),
                    'avatar' => $u->getBrochureFilename()
                );
                $res[$i] = $data;
            }

            return $this->json(array('error' => false, 'users' => $res));
        }catch (Exception $e) {
            print($e);
            return $this->json(array('error' => true));
        }

    }


    /**
     * @Route("/mobile/user/delete/{id}", name="mobile_user_delete", methods={"POST"})
     */
    public function MobileDeleteUser( $id )
    {

        try {
            $rep = $this->getDoctrine()->getRepository(Client::class);
            $m = $rep->find($id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($m);
            $em->flush();

            return $this->json(array('error' => false));
        } catch (Exception $e) {
            return $this->json(array('error' => true));
        }
    }



}
