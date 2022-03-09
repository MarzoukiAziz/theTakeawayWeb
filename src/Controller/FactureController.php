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
     * @Route("/admin/factures", name="listfacture")
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
     * @Route("/admin/factures/calander/{fou}/", name="facture_calendar")
     */
    public function calendrierFactures($fou): Response
    {
        $ing = $this->getDoctrine()->getRepository(Facture::class)
            ->createQueryBuilder('r')
            ->where('r.fournisseur=?1')
            ->setParameter(1, $fou)
            ->getQuery()
            ->getResult();
        $rdvs=[];
        foreach($ing as $r){
            $rdvs[] = [
                'id' => $r->getId(),
                'title'=>$r->getIngrediant()->getNom()." (".$r->getQuantite().")",
                'start' => $r->getDate()->add(new \DateInterval('PT' . $r->getHeure()->format('H') . 'H'))->format('Y-m-d H:i:s'),

            ];
        }

        $data = json_encode($rdvs);

        return $this->render('Facture/calendrier.html.twig', compact('data'));


    }

    /**
     * @Route("/admin/factures/add", name="choixFournisseur")
     */
    public function choisirFournisseur(): Response
    {

        $rep = $this->getDoctrine()->getRepository(Fournisseur::class);
        $res = $rep->findAll();
        return $this->render('Facture/step1.html.twig', [
            'res'=>$res,

        ]);
    }
    /**
     * @Route("/admin/factures/add/{idf}", name="choixRestaurant")
     */
    public function choixRestaurant($idf): Response
    {

        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res = $rep->findAll();

        return $this->render('Facture/step2.html.twig', [
            'res'=>$res,
            'idf'=>$idf,

        ]);
    }
    /**
     * @Route("/admin/factures/add/{idf}/{rid}", name="choixIngrediant")
     */
    public function choixIngrediant($idf,$rid): Response
    {
        $ing = $this->getDoctrine()->getRepository(Ingrediant::class)
            ->createQueryBuilder('r')
            ->where('r.restaurant=?1')
            ->setParameter(1, $rid)
            ->getQuery()
            ->getResult();


        return $this->render('Facture/step3.html.twig', [
            'res'=>$ing,
            'idf'=>$idf,
            'rid'=>$rid

        ]);
    }

    /**
     * @Route("/admin/factures/add/{idf}/{rid}/{iid}", name="addfacture")
     */
    public function addfacture(Request $request,$idf,$iid)

    {
        $fr = new Facture();
        $form = $this->createForm(FactureType::class,$fr);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $fournisseur = $this->getDoctrine()->getRepository(Fournisseur::class)->find($idf);
            $ingrediant = $this->getDoctrine()->getRepository(Ingrediant::class)->find($iid);
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
     * @Route("admin/factures/{id}/show", name="show")
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
     * @Route("admin/factures/{id}/udpate", name="updatefacture")
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
     * @Route("admin/factures/{id}/pdf", name="facturepdf")
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
        $html = $this->renderView('facture/show.html.twig', [
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
