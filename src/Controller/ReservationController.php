<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Client;
use App\Entity\Reservation;
use App\Entity\Restaurant;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/admin/reservations", name="admin-reservations-choose-restaurant")
     */
    public function choisirRestaurant(): Response
    {
        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res = $rep->findAll();
        return $this->render('reservation/admin/admin-choose-restaurant.html.twig', [
            'res'=>$res,
        ]);
    }


    /**
     * @Route("/admin/reservations/{rid}", name="admin-reservations-restaurant")
     */
    public function afficherReservations(Request $request,PaginatorInterface $paginator,$rid ): Response
    {
        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res = $rep->find($rid);
        if($res){
            $rev = $this->getDoctrine()->getRepository(Reservation::class)
                ->createQueryBuilder('r')
                ->where('r.restaurant=?1')
                ->setParameter(1, $rid)
                ->getQuery()
                ->getResult();
            $rev=$paginator->paginate(
                $rev,
                $request->query->getInt('page', 1),
                10
            );
            return $this->render('reservation/admin/admin-reservations.html.twig', [
                'rev'=>$rev,
                'res'=>$res,
            ]);
        }
        return $this->render('back-erreur.html.twig');
    }
    /**
     * @Route("/admin/reservations/{rid}/{id}/change-statut/{s}/{adminId}", name="admin-change-reservation-statut")
     */
    public function changerStatutReservation($rid,$id, $s,$adminId): Response
    {
        if($s=="Accepté" or $s == "Réfusé" or $s ="Annulé"){
            $rep = $this->getDoctrine()->getRepository(Reservation::class);
            $reservation = $rep->find($id);
            $rep2 = $this->getDoctrine()->getRepository(Admin::class);
            $admin = $rep2->find($adminId);
            if($reservation!=null && $admin != null)
            {
                $reservation->setStatut($s);
                $reservation->setAdminCharge($admin);
                $this->getDoctrine()->getManager()->flush();
            }
        }
        return $this->redirectToRoute('admin-reservations-restaurant',['rid'=>$rid]);
    }

    /**
     * @Route("/admin/reservations/client/{cid}", name="admin-reservations-client")
     */
    public function afficherReservationsClient(Request $request,PaginatorInterface $paginator,$cid ): Response
    {
        $rep = $this->getDoctrine()->getRepository(Client::class);
        $client = $rep->find($cid);
        if($client){
            $rev = $this->getDoctrine()->getRepository(Reservation::class)
                ->createQueryBuilder('r')
                ->where('r.clientId=?1')
                ->setParameter(1, $cid)
                ->getQuery()
                ->getResult();
            $rev=$paginator->paginate(
                $rev,
                $request->query->getInt('page', 1),
                10
            );
            return $this->render('reservation/admin/admin-reservations-client.html.twig', [
                'rev'=>$rev,
                'client'=>$client,
            ]);
        }
        return $this->render('back-erreur.html.twig');
    }




}
