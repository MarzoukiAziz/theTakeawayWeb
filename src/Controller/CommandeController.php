<?php

namespace App\Controller;
use App\Services\QrcodeService;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

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
////////////////////////front-office//////////////////////////////////
    /**
     * @Route("/commandes", name="commandes"))
     */
    //show the client reservations
    public function afficherCommandes(Request $request): Response
    {
        $rep = $this->getDoctrine()->getRepository(Client::class);
        $cmds = $this->getDoctrine()->getRepository(Commande::class)
            ->createQueryBuilder('r')
            ->where('r.client=?1')
            ->setParameter(1, $this->getUser()->getId())
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
        if ($commande == null or !$commande->getStatut("En attente")) {
            return $this->redirectToRoute("erreur-back");
        }

        $rep2 = $this->getDoctrine()->getRepository(Client::class);
        //check if this commande belongs to the current user
        if ($this->getUser() !=$commande->getClient()){
            return $this->redirectToRoute("erreur-front");
        }


        $commande->setStatut('Annulé');
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('commandes');
    }


    /////////////////////////////////back-office//////////////////////

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
    public function afficherCommandesAdmin(Request $request ,PaginatorInterface $paginator,$rid ): Response
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


        return $this->render('commande/admin/show.html.twig', [
            'commande' => $commande,
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
