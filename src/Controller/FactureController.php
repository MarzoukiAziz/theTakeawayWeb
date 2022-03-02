<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Fournisseur;
use App\Entity\Restaurant;
use App\Form\FactureType;
use App\Entity\Ingrediant;
use App\Form\IngrediantType;
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
     * @Route("/choixFournisseur", name="choixFournisseur")
     */
    public function choisirFournisseur(): Response
    {

        $rep = $this->getDoctrine()->getRepository(Fournisseur::class);
        $res = $rep->findAll();
        return $this->render('Facture/choose-ingrediant.html.twig', [
            'res'=>$res,

        ]);
    }
    /**
     * @Route("/choixIngrediant/{idf}", name="choixIngrediant")
     */
    public function choisirIngrediant($idf): Response
    {

        $rep = $this->getDoctrine()->getRepository(Ingrediant::class);
        $res = $rep->findAll();

        return $this->render('Facture/choose-fournisseur.html.twig', [
            'res'=>$res,
            'idf'=>$idf,

        ]);
    }
    /**
     * @Route("/addfacture/{id}/{id2}", name="addfacture")
     */
    public function addfacture(Request $request,$id,$id2)

    {
        $fr = new Facture();
        $form = $this->createForm(FactureType::class,$fr);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $fournisseur = $this->getDoctrine()->getRepository(Fournisseur::class)->find($id);
            $ingrediant = $this->getDoctrine()->getRepository(Ingrediant::class)->find($id2);
            $fr->setFournisseur($fournisseur);
            $fr->setIngrediant($ingrediant);
            $ingrediant->setQuantite($ingrediant->getQuantite()+$fr->getQuantite());
            $fr->setDate(new \DateTime());
            $fr->setHeure(new \DateTime());
            //save
            $em = $this->getDoctrine()->getManager();
            $em->persist($fr);
            $em->persist($ingrediant);
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
     * @Route("/show/{cid}", name="show")
     */
    public function show($cid)
    {
        return $this->render('facture/show.html.twig', [
            'factures' =>  $this->getDoctrine()->getRepository(Facture::class)->find($cid),
        ]);
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
        return $this->render("facture/update-facture.html.twig",array('f'=>$form->createView()));
    }




}
