<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Form\FactureType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Request;

class FactureController extends AbstractController
{
    /**
     * @Route("/facture", name="facture")
     */
    public function index(): Response
    {
        return $this->render('facture/index.html.twig', [
            'controller_name' => 'FactureController',
        ]);
    }
    /**
     * @Route("/addfacture", name="addfacture")
     */
    public function addfacture(Request $request)

    {
        $fr = new Facture();

        $form = $this->createForm(FactureType::class,$fr);

        $form->handleRequest($request);
        if ($form->isSubmitted() ) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fr);
            $em->flush();
            return $this->redirectToRoute('listfacture');
        }
        return $this->render("/facture/ajouter-facture.html.twig",array('f'=>$form->createView()));
    }

    /**
     * @Route("/listfacture", name="listfacture")
     */
    public function listfacture()
    {
        $fr = $this->getDoctrine()->getRepository(Facture::class)->findAll();
        return $this->render('facture/affiche-facture.html.twig', array("fr" => $fr));
    }




    /**
     * @Route("/deletefacture/{id}", name="deletefacture")
     */
    public function delete($id)
    {
        $f = $this->getDoctrine()->getRepository(Facture::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($f);
        $em->flush();
        return $this->redirectToRoute("listfacture");
    }

    /**
     * @Route("/updatefacture/{id}", name="updatefacture")
     */
    public function updatefacture(Request $request,$id)
    {
        $fr = $this->getDoctrine()->getRepository(Facture::class)->find($id);
        $form = $this->createForm(FactureType::class, $fr);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listfacture');
        }
        return $this->render("facture/update-facture.html.twig",array('form'=>$form->createView()));
    }




}
