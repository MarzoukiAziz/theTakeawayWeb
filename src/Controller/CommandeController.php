<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\ElementDetails;
use App\Entity\EtatElement;
use App\Entity\Restaurant;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class CommandeController extends AbstractController
{

    /***********************
     * ********front office**
     ************************/
    //implemnt this later
    public function calculatePrixTotale():Float{
        return 154;
    }
//métier
    /**
     * @Route("/restaurant/{rid}/menu", name="choose-menu", methods={"GET", "POST"})
     */
//    public function choose(Request $request, $rid,EntityManagerInterface $entityManager): Response
//    {
//        $resturant = $this->getDoctrine()->getRepository(Client::class)->find("$rid");
//        $eles = $this->getDoctrine()->getRepository(EtatElement::class)
//            ->createQueryBuilder('r')
//            ->where('r.restaurant=?1')
//            ->setParameter(1, $resturant->getId())
////            ->where('r.disponibilite=?2')
////            ->setParameter(2, true)
//            ->getQuery()
//            ->getResult();
//
//        return $this->render('commande/menu.html.twig', [
//            'res' => $resturant,
//            'eles'=>$eles
//        ]);
//    }
//
//

    /**
     * @Route("/restaurant/{rid}/commander", name="commande_new", methods={"GET", "POST"})
     */
    public function new(Request $request, $rid,EntityManagerInterface $entityManager): Response
    {

        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
        $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->find($rid);
        if($restaurant == null){
            $this->redirectToRoute("front-erreur");
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $commande->setRestaurant($restaurant);
            //to change later
            $client = $this->getDoctrine()->getRepository(Client::class)->find("1");
            $commande->setClientId($client);
            $commande->setStatut("En Attente");
            $commande->setPrixTotal($this->calculatePrixTotale());
            $date = new \DateTime();
            $commande->setDate($date);
            //par defaut success à traiter plus tard
            $commande->setStatutPaiement("Success");
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('commandes', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/commandes", name="commandes"))
     */
    //show the client reservations
    public function afficherCommandes(Request $request): Response
    {
        $rep = $this->getDoctrine()->getRepository(Client::class);
        //à changer
        $client = $rep->find("1");
        //getting the reservations by client id
        $cmds = $this->getDoctrine()->getRepository(Commande::class)
            ->createQueryBuilder('r')
            ->where('r.client=?1')
            ->setParameter(1, $client->getId())
            ->getQuery()
            ->getResult();

        return $this->render('commande/index.html.twig', [
            'commandes' => $cmds,
        ]);

    }


    /**
     * @Route("/commandes/{cid}", name="commande_show", methods={"GET"})
     */
    public function afficherUneCommande(Request $request, $cid): Response
    {

        return $this->render('commande/show.html.twig', [
            'commande' =>  $this->getDoctrine()->getRepository(Commande::class)->find($cid),
        ]);
    }
    /**
     * @Route("/commande/{cid}/cancel", name="client-cancel-commande")
     */
    //change the status of a reservation
    public function annulerUneCommande($cid): Response
    {
        $rep = $this->getDoctrine()->getRepository(Commande::class);
        $commande = $rep->find($cid);
        //check if the parameters are correct
        if ($commande == null or !$commande->getStatut("En Attente")) {
            return $this->redirectToRoute("erreur-back");
        }

        ///important
        ///change this with the current user  id;
        ///

        $rep2 = $this->getDoctrine()->getRepository(Client::class);
        $client = $rep2->find('1');
        //check if this commande belongs to the current user
        if ($client !=$commande->getClient()){
            return $this->redirectToRoute("erreur-front");
        }


        $commande->setStatut('Annulé');
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('commandes');
    }




    /************************************
     * Back Office
     */

    /**
     * @Route("/admin/commandes", name="admin-commande-choose-restaurant")
     */
    //show restaurant list before showing the reservations list
    public function chooseRestaurant(): Response
    {
        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res = $rep->findAll();
        return $this->render('commande/admin/choose-restaurant.html.twig', [
            'res' => $res,
        ]);
    }



    /**
     * @Route("/admin/commandes/{rid}", name="commandes-admin", methods={"GET"})
     */
    public function afficherCommandesAdmin(Request $request ,PaginatorInterface $paginator,$rid): Response
    {
        //warning find by client id

        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res= $rep->find($rid);
        //check if the restaurant id is valid
        if ($res == null) {
            return $this->redirectToRoute("erreur-back");
        }
        //getting the reservations by restaurant id
        $cmds= $this->getDoctrine()->getRepository(Commande::class)
            ->createQueryBuilder('r')
            ->where('r.restaurant=?1')
            ->setParameter(1, $rid)
            ->getQuery()
            ->getResult();
        $cmds = $paginator->paginate(
            $cmds,
            $request->query->getInt('page',1),
            4
        );
//        if ($request->isMethod("POST")){
//            $restaurant = $request->get('restaurant');
//            $cmds = $donnees->getRepository("CommandeController:Commande")->findBy(array('restaurant'=>restaurant));
//        }
        return $this->render('commande/admin/index.html.twig', [
            'cmds' => $cmds,
            'res' => $res,
        ]);
    }
    /**
     * @Route("/admin/commandes/{rid}/{cid}", name="commande-show-admin", methods={"GET"})
     */
    public function afficherUneCommandeAdmin(Request $request, $cid ,$rid): Response
    {

        $commande = $this->getDoctrine()->getRepository(Commande::class)->find($cid);

//        $details  = $this->getDoctrine()->getRepository(ElementDetails::class)
//            ->createQueryBuilder('d')
//            ->andWhere('d.commande=?1')
//            ->setParameter(1, $commande)
//            ->getQuery()
//            ->getResult();

        return $this->render('commande/admin/show.html.twig', [
            'commande' => $commande,
          //  'details' => $details
        ]);
    }

    /**
     * @Route("/admin/commandes/{rid}/{cid}/update", name="commande-update-admin", methods={"POST"})
     */
    public function updateUneCommandeAdmin(Request $request, $cid ,$rid ): Response
    {
        $c = $this->getDoctrine()->getRepository(Commande::class)->find($cid);
        $data = $request->request;
        $c->setStatut($data->get('statut'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($c);
        $em->flush();
        return $this->redirectToRoute("commande-show-admin",['cid'=>$cid ,'rid' => $rid, ]);

    }

    /**
     * @Route("/admin/commandes/{rid}/{cid}/delete", name="commande-delete-admin", methods={"POST"})
     */
    public function deleteCommandeByAdmin(Request $request, $cid ,$rid): Response
    {
        $em = $this->getDoctrine()->getManager();
        $cmd = $em->getRepository(Commande::class)->find($cid);
        $em = $this->getDoctrine()->getManager();
        $em->remove($cmd);
        $em->flush();
        return $this->redirectToRoute('commandes-admin', ['rid'=>$rid], Response::HTTP_SEE_OTHER);
    }





}
