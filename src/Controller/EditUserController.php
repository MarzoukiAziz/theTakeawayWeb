<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\EditeProfileType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditUserController extends AbstractController
{
    /**
     * @Route("/hello", name="dali", methods={"GET", "POST"})
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(EditeProfileType::class);
        $form->handleRequest($request);
        if(! $this->getUser()){
            return $this->redirectToRoute("registre");
        }
        return $this->render('edit_user/index.html.twig', [
            'f' => $form->createView(),


        ]);
    }
    /**
     * @Route("/edit", name="users_profil_modifier", methods={"GET", "POST"})
     */
    public function editProfile(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditeProfileType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('users_profil_modifier');
        }

        return $this->render('edit_user/index.html.twig', [
            'f' => $form->createView(),
        ]);
    }

}
