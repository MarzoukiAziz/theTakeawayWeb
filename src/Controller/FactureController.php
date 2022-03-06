<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Fournisseur;
use App\Entity\Restaurant;
use App\Form\FactureType;
use App\Entity\Ingrediant;

use App\Form\PropertySearchType;
use App\Entity\PropertySearch;
use Dompdf\Dompdf;
use Dompdf\Options;
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
/*
    /**
     * @Route("/listfacture", name="listfacture")

    public function listfacture(Request $request)
    {
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class,$propertySearch);
        $form->handleRequest($request);
        //initialement le tableau des articles est vide,
        //c.a.d on affiche les articles que lorsque l'utilisateur clique sur le bouton rechercher
        $factures= [];

        if($form->isSubmitted() && $form->isValid()) {
            //on récupère le nom d'article tapé dans le formulaire
            $fournisseur = $propertySearch->getFournisseur();
            if ($fournisseur!="")
                //si on a fourni un nom d'article on affiche tous les articles ayant ce nom
                $factures= $this->getDoctrine()->getRepository(Facture::class)->findBy(['fournisseur' => $fournisseur] );
            else
                //si si aucun nom n'est fourni on affiche tous les articles
                $factures= $this->getDoctrine()->getRepository(Facture::class)->findAll();
        }
        return  $this->render('facture/affiche-facture.html.twig',[ 'form' =>$form->createView(), 'factures' => $factures]);
    }*/


    /**
     * @Route("/show/{id}", name="show")
     */
    public function show($id)
    {

        return $this->render('facture/show.html.twig', [
            'fr' =>  $this->getDoctrine()->getRepository(Facture::class)->find($id),
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


    /**
     * @Route("/listfacture", name="listfacture")
     * Method({"GET", "POST"})
     */
    public function factureparfournisseur(Request $request) {
        $PropertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class,$PropertySearch);
        $form->handleRequest($request);

        $factures= [];

        if($form->isSubmitted() && $form->isValid()) {
            $fournisseur = $PropertySearch->getFournisseur();

            if ($fournisseur!="")
            {
               $factures= $this->getDoctrine()->getRepository(Facture::class)->findBy(['fournisseur' => $fournisseur
               ] );
            }
            else
                $factures= $this->getDoctrine()->getRepository(Facture::class)->findAll();
        }

        return $this->render('facture/affiche-facture-par-fou.html.twig',['fr' => $form->createView(),'factures' => $factures]);
    }
    /**
     * @Route("/facture/{id}/pdf", name="facturepdf")
     */
    public function pdf($id)
    {
        $p=$this->getDoctrine()->getRepository(Facture::class)->find($id);

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('facture/mypdf.html.twig', [
            'title' => "Welcome to our PDF Test",
            "fr"=>$p

        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
        return $this->redirectToRoute("listfacture");
    }




}
