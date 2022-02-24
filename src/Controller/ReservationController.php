<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Client;
use App\Entity\Reservation;
use App\Entity\Restaurant;
use App\Entity\Table;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class ReservationController extends AbstractController
{
    /*******************************************
     ************ BACK OFFICE*******************
     * *****************************************/


    /**
     * @Route("/admin/reservations", name="admin-reservations-choose-restaurant")
     */
    //show restaurant list before showing the reservations list
    public function chooseRestaurant(): Response
    {
        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res = $rep->findAll();
        return $this->render('reservation/admin/admin-choose-restaurant.html.twig', [
            'res' => $res,
        ]);
    }

    /**
     * @Route("/admin/reservations/{rid}", name="admin-reservations-restaurant")
     */
    //show all reservations of the chosen restaurant
    public function showReservationsByRestaurant(Request $request, PaginatorInterface $paginator, $rid): Response
    {
        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res = $rep->find($rid);
        //check if the restaurant id is valid
        if ($res == null) {
            return $this->redirectToRoute("erreur-back");
        }
        //getting the reservations by restaurant id
        $rev = $this->getDoctrine()->getRepository(Reservation::class)
            ->createQueryBuilder('r')
            ->where('r.restaurant=?1')
            ->setParameter(1, $rid)
            ->getQuery()
            ->getResult();
        //paginating the reservations by 10 per page
        $rev = $paginator->paginate(
            $rev,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('reservation/admin/admin-reservations.html.twig', [
            'rev' => $rev,
            'res' => $res,
        ]);
    }

    /**
     * @Route("/admin/reservations/{rid}/{id}/change-statut/{s}/", name="admin-change-reservation-statut")
     */
    //change the status of a reservation
    public function updateReservationStatutByAdmin($rid, $id, $s): Response
    {
        $rep = $this->getDoctrine()->getRepository(Reservation::class);
        $reservation = $rep->find($id);
        ///important
        ///change this with the current admin  id;
        $adminId = 1;
        $rep2 = $this->getDoctrine()->getRepository(Client::class);
        $admin = $rep2->find($adminId);
        //check if the parameters are correct
        if (!($s == "Accepté" or $s == "Réfusé" or $s = "Annulé") or $admin == null or $reservation == null) {
            return $this->redirectToRoute("erreur-back");
        }
        $reservation->setStatut($s);
        $reservation->setAdminCharge($admin);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('admin-reservations-restaurant', ['rid' => $rid]);
    }

    /**
     * @Route("/admin/reservations/client/{cid}", name="admin-reservations-client")
     */
    //show all reservations of a selected client
    public function showReservationsByClient(Request $request, PaginatorInterface $paginator, $cid): Response
    {
        $rep = $this->getDoctrine()->getRepository(Client::class);
        $client = $rep->find($cid);
        //checking the client
        if ($client == null) {
            return $this->redirectToRoute("erreur-back");
        }
        //getting the reservations by client id
        $rev = $this->getDoctrine()->getRepository(Reservation::class)
            ->createQueryBuilder('r')
            ->where('r.clientId=?1')
            ->setParameter(1, $cid)
            ->getQuery()
            ->getResult();
        //paginating the reservations by 10 per page
        $rev = $paginator->paginate(
            $rev,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('reservation/admin/admin-reservations-client.html.twig', [
            'rev' => $rev,
            'client' => $client,
        ]);

    }

    /*******************************************
     ************ FRONT OFFICE*******************
     * *****************************************/


    //create search form to find available tables
    public function searchForm()
    {
        //limit the available dates at the current and the next month.
        $years = array(date("Y"));
        $months = array(date("m"), date("m") + 1);
        if (date("m") == 12) {
            $years = array(date("Y"), date("Y") + 1);
        }
        $defaultData = ['message' => ''];
        $form = $this->createFormBuilder($defaultData)
            ->add('date', DateType::class, [
                'widget' => 'choice',
                'months' => $months,
                'years' => $years,
            ])
            ->add('heureArrive', TimeType::class, array(
                'widget' => 'choice',
                'hours' => array(11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21),
                'minutes' => array(0, 15, 30, 45)
            ))
            ->add('Duree', ChoiceType::class, [
                'choices' => [
                    '15 Minutes' => 15,
                    '30 Minutes' => 30,
                    '45 Minuets' => 45
                ],

            ])
            ->add('nbPersonne', ChoiceType::class, [
                'choices' => [
                    '1 Personne' => 1,
                    '2 Personnes' => 2,
                    '3 Personnes' => 3,
                    '4 Personnes' => 4,
                    '5 Personnes' => 5,
                    '6 Personnes' => 6,
                    '7 Personnes' => 7,
                    '8 Personnes' => 8,
                    '9 Personnes' => 9,
                    '10 Personnes' => 10,

                ],

            ])
            ->add("save", SubmitType::class)
            ->getForm();
        return $form;
    }

    /**
     * @Route("/restaurant/{rid}/reserver/", name="reserve")
     */
    //
    public function reserve(Request $request, $rid): Response
    {
        //create the search form
        $form = $this->searchForm();
        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res = $rep->find($rid);
        //check if restaurant id is valid
        if ($res == null) {
            return $this->redirectToRoute("erreur-front");
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //getting the reservations by date and restaurant id
            $data = $form->getData();

            $currentReservations = $this->getDoctrine()->getRepository(Reservation::class)
                ->createQueryBuilder('r')
                ->where('r.date=?1')
                ->setParameter(1, $data["date"])
                ->andWhere('r.restaurant=?2')
                ->setParameter(2, $res)
                ->getQuery()
                ->getResult();
            //create "heureDepart" after adding the duration
            $heureDepart = clone $data["heureArrive"];
            $heureDepart->add(new \DateInterval('PT' . $data["Duree"] . 'M'));
            $heureDepart = $heureDepart->format('H:i');
            //getting all tables of the restaurant
            $allTables = $res->getTables();
            //finding the unavailable tables which already have a reservation at the given time //work with ids
            $ut = array();
            foreach ($currentReservations as $cr) {
                if (($cr->getHeureDepart() >= $data["heureArrive"] and $cr->getHeureArrive() <= $data["heureArrive"])
                    or ($cr->getHeureArrive() >= $heureDepart and $cr->getHeureDepart() <= $heureDepart)) {
                    foreach ($cr->getTables() as $tab) {
                        array_push($ut, $tab->getId());
                    }
                }
            }
            //build two arrays of freetables and unavailable tables
            $ft = array();
            foreach ($allTables as $f) {
                array_push($ft, $f->getId());
            }
            $ft = array_diff($ft, $ut);
            $freeTables = array();
            $unavailableTables = array();
            $rep = $this->getDoctrine()->getRepository(Table::class);
            foreach ($ft as $x) {
                array_push($freeTables, $rep->find($x));
            }
            foreach ($ut as $x) {
                array_push($unavailableTables, $rep->find($x));
            }
            //create a reservation object with the searched params to past it to the twig page
            $rev = new Reservation();
            $rev->setRestaurant($res);
            $rev->setDate($data['date']);
            $rev->setHeureArrive($data['heureArrive']);
            $h = date_create($heureDepart);
            $rev->setHeureDepart($h);
            $rev->setNbPersonne($data['nbPersonne']);
            //rendring Step Two
            return $this->render(
                "reservation/reserve-step-two.html.twig", [
                'freeTables' => $freeTables,
                'unavailableTables' => $unavailableTables,
                'rev' => $rev,
            ]);

        }
        //Rendring Step One
        return $this->render("reservation/reserve-step-one.html.twig", [
            'f' => $form->createView(),
            'res' => $res
        ]);
    }

    /**
     * @Route("/restaurant/{rid}/reserver/confirmed/", name="reserveation-confirmed")
     */
    public function createReservation(Request $request, $rid)
    {
        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res = $rep->find($rid);
        //// important
        /// change this test client with the real one
        $exampleClient = $this->getDoctrine()->getRepository(Client::class)->find('1');
        $data = $request->request;
        if($res == null or $exampleClient ==null){
            return $this->redirectToRoute("erreur-front");
        }
        //setting the data of the new reservation object
        $newRev = new Reservation();
        $newRev->setRestaurant($res);
        $newRev->setDate(date_create($data->get('date')));
        $newRev->setClientId($exampleClient);//will be changed later
        $newRev->setHeureArrive(date_create($data->get('ha')));
        $newRev->setHeureDepart(date_create($data->get('hd')));
        $newRev->setNbPersonne($data->getInt('nb'));
        $newRev->setStatut("En Attente");
        //finding and checking the selected tables
        $tabs = $this->getDoctrine()->getRepository(Table::class)->findAll();
        foreach ($tabs as $t) {
            if (array_search($t->getId(), $data->keys()) == true) {
                $newRev->addTable($t);
            }
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($newRev);
        $em->flush();
        $this->addFlash('message', "Reservation crée avec succés");
        return $this->redirectToRoute("main");
    }

    /**
     * @Route("/reservations", name="reservations")
     */
    //show the client reservations
    public function showClientReservations(Request $request, PaginatorInterface $paginator): Response
    {
        $rep = $this->getDoctrine()->getRepository(Client::class);
        //important change it later
        $client = $rep->find("1");
        //checking the client
        if ($client == null) {
            return $this->redirectToRoute("erreur-back");
        }
        //getting the reservations by client id
        $rev = $this->getDoctrine()->getRepository(Reservation::class)
            ->createQueryBuilder('r')
            ->where('r.clientId=?1')
            ->setParameter(1, $client->getId())
            ->getQuery()
            ->getResult();
        //paginating the reservations by 10 per page
        $rev = $paginator->paginate(
            $rev,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('reservation/reservations-client.html.twig', [
            'rev' => $rev,
            'client' => $client,
        ]);

    }
    /**
     * @Route("/reservations/{rid}/cancel", name="client-cancel-reservation")
     */
    //change the status of a reservation
    public function cancel($rid): Response
    {
        $rep = $this->getDoctrine()->getRepository(Reservation::class);
        $reservation = $rep->find($rid);
        //check if the parameters are correct
        if ($reservation == null) {
            return $this->redirectToRoute("erreur-back");
        }

        ///important
        ///change this with the current user  id;
        ///

        $rep2 = $this->getDoctrine()->getRepository(Client::class);
        $client = $rep2->find('1');
        //check if this reservation belongs to the current user
        if ($client !=$reservation->getClientId()){
            return $this->redirectToRoute("erreur-front");
        }


        $reservation->setStatut('Annulé');
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('reservations');
    }


}
