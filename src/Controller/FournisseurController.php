<?php

namespace App\Controller;

use App\Entity\Facture;
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
     * @Route("/admin/fournisseur/add", name="add")
    */
    public function add(Request $request)

    {
        $fr = new Fournisseur();
        $form = $this->createForm(FournisseurType::class,$fr);

        $form->handleRequest($request);
       if ($form->isSubmitted() && $form->isValid() ) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fr);
            $em->flush();
            return $this->redirectToRoute('listfournisseur');
       }
      return $this->render("/fournisseur/ajouter-fournisseur.html.twig",array('f'=>$form->createView()));
 }

    /**
     * @Route("/admin/fournisseur", name="listfournisseur")
     */
    public function listfournisseur()
    {
        $fr = $this->getDoctrine()->getRepository(Fournisseur::class)->findAll();
        return $this->render('fournisseur/affiche-fournisseur.html.twig', array("fr" => $fr));
    }




    /**
     * @Route("/admin/fournisseur/{id}/delete", name="deletefournisseur")
     */
    public function deleteFournisseur($id)
    {
        $f = $this->getDoctrine()->getRepository(Fournisseur::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $factures = $this->getDoctrine()->getRepository(Facture::class)->findAll();
        foreach ($factures as $fac){
            if($fac->getFournisseur()==$f){
                $em->remove($fac);
            }
        }
        $em->remove($f);
        $em->flush();
        return $this->redirectToRoute("listfournisseur");
    }

    /**
     * @Route("/admin/fournisseur/{id}/update", name="updatefournisseur")
     */
    public function updatefournisseur(Request $request,$id)
    {
        $fr = $this->getDoctrine()->getRepository(Fournisseur::class)->find($id);
        $form = $this->createForm(FournisseurType::class, $fr);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listfournisseur');
        }
        return $this->render("fournisseur/update-fournisseur.html.twig",array('f'=>$form->createView()));
    }






}



