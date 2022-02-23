<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class FournisseurController extends AbstractController
{
    /**
     * @Route("/fournisseur", name="fournisseur")
     */
    public function index(): Response
    {
        return $this->render('fournisseur/index.html.twig', [
            'controller_name' => 'FournisseurController',
        ]);
    }
    /**
     * @Route("/addfournisseur", name="add")
    */
    public function add(Request $request)

    {
        $fr = new Fournisseur();
        $form = $this->createForm(FournisseurType::class,$fr);

        $form->handleRequest($request);
       if ($form->isSubmitted() ) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fr);
            $em->flush();
            return $this->redirectToRoute('listfournisseur');
       }
      return $this->render("/fournisseur/ajouter-fournisseur.html.twig",array('f'=>$form->createView()));
 }

    /**
     * @Route("/listfournisseur", name="listfournisseur")
     */
    public function listfournisseur()
    {
        $fr = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        return $this->render('fournisseur/affiche-fournisseur.html.twig', array("fr" => $fr));
    }




    /**
     * @Route("/deletefournisseur/{id}", name="deletefournisseur")
     */
    public function delete($id)
    {
        $f = $this->getDoctrine()->getRepository(Fournisseur::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($f);
        $em->flush();
        return $this->redirectToRoute("listfournisseur");
    }

    /**
     * @Route("/updatefournisseur/{id}", name="updatefournisseur")
     */
    public function updatefournisseur(Request $request,$id)
    {
        $fr = $this->getDoctrine()->getRepository(Fournisseur::class)->find($id);
        $form = $this->createForm(FournisseurType::class, $fr);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listfournisseur');
        }
        return $this->render("fournisseur/update-fournisseur.html.twig",array('form'=>$form->createView()));
    }

    /**
     * @Route("/showClassroom/{id}", name="showClassroom")
     */
   /** public function show($id)
    {
        $fournisseur = $this->getDoctrine()->getRepository(Fournisseur::class)->find($id);
        $fournisseur= $this->getDoctrine()->getRepository(Fournisseur::class)->($fournisseur->getId());
        return $this->render('classroom/show.html.twig', [
            "classroom" => $classroom,
            "students"=>$students]);
    }**/





}



