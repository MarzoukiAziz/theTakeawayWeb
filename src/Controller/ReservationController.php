<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Client;
use App\Entity\Reservation;
use App\Entity\Restaurant;
use App\Entity\Table;
use App\Repository\ReservationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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
            ->orderBy('r.date', 'DESC')
            ->getQuery()
            ->getResult();
        //paginating the reservations by 8 per page
        $rev = $paginator->paginate(
            $rev,
            $request->query->getInt('page', 1),
            8
        );
        return $this->render('reservation/admin/admin-reservations.html.twig', [
            'rev' => $rev,
            'res' => $res,
            'filter' => ""
        ]);
    }



    /**
     * @Route("/admin/reservations/{rid}/sort-by-statut/{statut}", name="admin-sort-reservation-statut")
     */
    //show all reservations of the chosen restaurant
    public function showReservationsSortedByRestaurantAndStatut(Request $request, PaginatorInterface $paginator, $rid, $statut): Response
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
            ->andWhere('r.statut=?2')
            ->setParameter(2, $statut)
            ->orderBy('r.date', 'DESC')
            ->getQuery()
            ->getResult();
        //paginating the reservations by 8 per page
        $rev = $paginator->paginate(
            $rev,
            $request->query->getInt('page', 1),
            8
        );
        return $this->render('reservation/admin/admin-reservations.html.twig', [
            'rev' => $rev,
            'res' => $res,
            'filter' => $statut
        ]);
    }


    /**
     * @Route("/admin/reservations/{rid}/sort-by-date/{date}", name="admin-sort-reservation-date")
     */
    //show all reservations of the chosen restaurant
    public function showReservationsSortedByRestaurantAndDate(Request $request, PaginatorInterface $paginator, $rid, $date): Response
    {
        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res = $rep->find($rid);
        //check if the restaurant id is valid
        if ($res == null) {
            return $this->redirectToRoute("erreur-back");
        }
        //date loading
        $d = new \DateTime();
        $d->setTime(0, 0);
        if ($date == "Demain") {
            $d->modify("+24 hour");
        }

        //getting the reservations by restaurant id
        $rev = $this->getDoctrine()->getRepository(Reservation::class)
            ->createQueryBuilder('r')
            ->where('r.restaurant=?1')
            ->setParameter(1, $rid)
            ->andWhere('r.date=?2')
            ->setParameter(2, $d)
            ->orderBy('r.date', 'DESC')
            ->getQuery()
            ->getResult();
        //paginating the reservations by 8 per page
        $rev = $paginator->paginate(
            $rev,
            $request->query->getInt('page', 1),
            8
        );
        return $this->render('reservation/admin/admin-reservations.html.twig', [
            'rev' => $rev,
            'res' => $res,
            'filter' => $date
        ]);
    }


    /**
     * @Route("/admin/reservations/{rid}/{id}/change-statut/{s}/", name="admin-change-reservation-statut")
     */
    //change the status of a reservation
    public function updateReservationStatutByAdmin($rid, $id, $s, MailerInterface $mailer): Response
    {
        $rep = $this->getDoctrine()->getRepository(Reservation::class);
        $reservation = $rep->find($id);
        if (!($s == "Accepté" or $s == "Réfusé" or $s = "Annulé") or $reservation == null) {
            return $this->redirectToRoute("erreur-back");
        }
        $reservation->setStatut($s);
        $reservation->setAdminCharge($this->getUser());
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('success', 'Statut changé avec succès!');


        $email = (
        new Email())
            ->from('thetakeaway.esprit@gmail.com')
            ->to($reservation->getClientId()->getEmail());

        if ($s == "Accepté") {
            $email->subject('Reservation Accpetée')
                ->html('
<h2>R&eacute;servation accept&eacute;e</h2>

<p>Bonjour ' . $reservation->getClientId()->getNom() . ',</p>
<p>
 Nous avons le plaisir de vous informer que votre réservation pour ' . $reservation->getRestaurant()->getNom() . ' le ' . $reservation->getDate()->format('d M Y') . ' , a été acceptée.
</p>');
        } else if ($s == "Réfusé") {
            $email->subject('Reservation Réfusé')
                ->html('
<h2>R&eacute;servation Refuse</h2>

<p>Bonjour ' . $reservation->getClientId()->getNom() . ',</p>
<p>
 Nous sommes désolé de vous informer que votre réservation pour ' . $reservation->getRestaurant()->getNom() . ' le ' . $reservation->getDate()->format('d M Y') . ' , a été réfusée.
</p>');
        } else if ($s == "Annulé") {
            $email->subject('Reservation Annulée')
                ->html('
<h2>R&eacute;servation accept&eacute;e</h2>

<p>Bonjour ' . $reservation->getClientId()->getNom() . ',</p>
<p>
 Nous sommes désolé de vous informer que votre réservation pour ' . $reservation->getRestaurant()->getNom() . ' le ' . $reservation->getDate()->format('d M Y') . ' , a été annulée.
</p>');
        }


        $mailer->send($email);

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
        $defaultData = ['message' => ''];
        $form = $this->createFormBuilder($defaultData)
            ->add('date', DateType::class, [
                'label' => 'Date Commande',
                'widget' => 'single_text',
                'attr' => [
                    'placeholder' => 'Date',
                ]
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
            $today = new \DateTime("now");
            $today->setTime(0, 0);
            if ($data["date"] < $today) {
                $this->addFlash('danger', 'Date Invalide');
                return $this->redirect($request->getUri());
            }
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
                if ($cr->getStatut() == "Accepté" or $cr->getStatut() == "En Attente") {
                    if (($cr->getHeureDepart() >= $data["heureArrive"] and $cr->getHeureArrive() <= $data["heureArrive"])
                        or ($cr->getHeureArrive() >= $heureDepart and $cr->getHeureDepart() <= $heureDepart)) {
                        foreach ($cr->getTables() as $tab) {
                            array_push($ut, $tab->getId());
                        }
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
        $data = $request->request;
        if ($res == null) {
            return $this->redirectToRoute("erreur-front");
        }
        //setting the data of the new reservation object
        $newRev = new Reservation();
        $newRev->setRestaurant($res);
        $newRev->setDate(date_create($data->get('date')));
        $newRev->setClientId($this->getUser());
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
        if ($newRev->getTables()->count() == 0) {
            $this->addFlash("danger","Pas de table séléctionnée!");
            return $this->redirectToRoute("reserve", ['rid' => $rid]);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($newRev);
        $em->flush();
        $this->addFlash("success","Réservation ajoutée avec succès!");
        return $this->redirectToRoute("reservations");
    }

    /**
     * @Route("/reservations", name="reservations")
     */
    //show the client all reservations
    public function showClientReservations(Request $request, PaginatorInterface $paginator): Response
    {
        //getting the reservations by client id
        $rev = $this->getDoctrine()->getRepository(Reservation::class)
            ->createQueryBuilder('r')
            ->where('r.clientId=?1')
            ->setParameter(1, $this->getUser()->getId())
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
            "filter"=>"",
            'res'=>$this->getDoctrine()->getRepository(Restaurant::class)->findAll()
        ]);

    }
    /**
     * @Route("/reservations/filter-by-restaurant/{rid}", name="reservations-by-restaurant")
     */
    //show the client reservations by restaurant
    public function showClientReservationsByRestaurant(Request $request, $rid, PaginatorInterface $paginator): Response
    {
        $restaurants=$this->getDoctrine()->getRepository(Restaurant::class)->findAll();
        $restaurant=$this->getDoctrine()->getRepository(Restaurant::class)->find($rid);
        //getting the reservations by client id
        $rev = $this->getDoctrine()->getRepository(Reservation::class)
            ->createQueryBuilder('r')
            ->where('r.clientId=?1')
            ->setParameter(1, $this->getUser()->getId())
            ->Andwhere('r.restaurant=?2')
            ->setParameter(2, $restaurant)
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
            "filter"=>$restaurant->getNom(),
            'res'=>$restaurants
        ]);

    }


    /**
     * @Route("/reservations/filter-by-date/{date}", name="reservations-date")
     */
    //show the client reservations by date
    public function showClientReservationsByDate(Request $request, PaginatorInterface $paginator,$date): Response
    {
        //date loading
        $d = new \DateTime();
        $d->setTime(0, 0);
        if ($date == "Demain") {
            $d->modify("+24 hour");
        }
        //getting the reservations by client id
        $rev = $this->getDoctrine()->getRepository(Reservation::class)
            ->createQueryBuilder('r')
            ->where('r.clientId=?1')
            ->setParameter(1, $this->getUser()->getId())
            ->andWhere('r.date=?2')
            ->setParameter(2, $d)
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
            'filter'=>$date
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
        //check if this reservation belongs to the current user
        if ($this->getUser() != $reservation->getClientId()) {
            return $this->redirectToRoute("erreur-front");
        }

        $reservation->setStatut('Annulé');
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash("success","Réservation annulée avec succès!");
        return $this->redirectToRoute('reservations');
    }
    /**
     * @Route("/admin/calendar/{rid}", name="calendar")
     */
    public function calendar(ReservationRepository $reservationRepository,$rid)
    {
        $rev = $this->getDoctrine()->getRepository(Reservation::class)
            ->createQueryBuilder('r')
            ->where('r.restaurant=?1')
            ->setParameter(1, $rid)
            ->orderBy('r.date', 'DESC')
            ->getQuery()
            ->getResult();
        $rdvs=[] ;

        foreach($rev as $r){
            $rdvs[] = [
                'id' => $r->getId(),
                'title'=>$r->getClientId()->getNom(),
                'titre'=>$r->getClientId()->getNom(),
                'start' => $r->getDate()->add(new \DateInterval('PT' . $r->getHeureArrive()->format('H') . 'H'))->format('Y-m-d H:i:s'),

                'service' => $r->getNbPersonne(),
                'telephonenum' => $r->getClientId()->getNumTel(),

            ];
        }

        $data = json_encode($rdvs);

        return $this->render('reservation/admin/calendar.html.twig', compact('data'));
    }

/////////////////MOBILE SERVICE//////////////

    /**
     * @Route("/mobile/rev/", name="mobile_rev", methods={"GET"})
     */
    public function mobileReservations( Request $request)
    {

        try {
            $rep = $this->getDoctrine()->getRepository(Reservation::class);
            $rev = $rep->findAll();

            $res = array();

            for ($i = 0; $i < sizeof($rev); $i++) {

                $t = $rev[$i]->getTables();
                $tables = array();
                for ($j = 0; $j < sizeof($t); $j++) {
                    $tab = array(
                        "id" => $t[$j]->getId(),
                        "posX" => $t[$j]->getPosX(),
                        "posY" => $t[$j]->getPosY(),
                        "nbPalces" => $t[$j]->getNbPalces(),
                        "numero" => $t[$j]->getNumero(),
                        "restaurant_id" => $t[$j]->getRestaurantId()->getId(),
                    );
                    $tables[$j] = $tab;
                }


                $data = array(
                    'id' => $rev[$i]->getId(),
                    'date' => $rev[$i]->getDate(),
                    'heureArrive' => $rev[$i]->getHeureArrive(),
                    'heureDepart' => $rev[$i]->getHeureDepart(),
                    'nbPersonne' => $rev[$i]->getNbPersonne(),
                    'clientId' => $rev[$i]->getClientId()->getId(),
                    'restaurant' => $rev[$i]->getRestaurant()->getId(),
                    'statut' => $rev[$i]->getStatut(),
                    'tables' => $tables
                );
                $res[$i] = $data;
            }

            return $this->json(array('error' => false, 'res' => $res));
        } catch (Exception $e) {
            print($e);
            return $this->json(array('error' => true));
        }
    }


    /**
     * @Route("/mobile/rev/change/", name="menu_rev_change_statut", methods={"POST"})
     */
    public function MobileChangeStatutReservation(Request $request): Response
    {
        try {
            $id = $request->get("id");
            $statut = $request->get('statut');
            $rep = $this->getDoctrine()->getRepository(Reservation::class);
            $rev=$rep->find($id);
            $rev->setStatut($statut);
            $em = $this->getDoctrine()->getManager();
            $em->flush();


            return $this->json(array('error' => false));
        } catch (Exception $e) {
            print($e);
            return $this->json(array('error' => true));
        }
    }




    /**
     * @Route("/mobile/tables/{rid}/{date}/{ha}/{d}/{nb}", name="free_tables-mobile")
     */

    public function mobileGetTablesDispo(Request $request, $rid,$date,$ha,$d,$nb): Response
    {
            try{
            //getting the reservations by date and restaurant id
            $today = new \DateTime("now");
            $today->setTime(0, 0);
            $res = $this->getDoctrine()->getRepository(Restaurant::class)->find($rid);
           $currentReservations = $this->getDoctrine()->getRepository(Reservation::class)
                ->createQueryBuilder('r')
                ->where('r.date=?1')
                ->setParameter(1, $date)
                ->andWhere('r.restaurant=?2')
                ->setParameter(2, $res)
                ->getQuery()
                ->getResult();
               $heureDepart = date_create($ha);
               $heureDepart->add(new \DateInterval('PT' . $d . 'M'));
               $heureDepart = $heureDepart->format('H:i');
               $allTables = $res->getTables();
               $ut = array();
                     foreach ($currentReservations as $cr) {
                        if ($cr->getStatut() == "Accepté" or $cr->getStatut() == "En Attente") {
                            if (($cr->getHeureDepart() >= date_create($ha) and $cr->getHeureArrive() <= date_create($ha))
                                or ($cr->getHeureArrive() >= $heureDepart and $cr->getHeureDepart() <= $heureDepart)) {
                                foreach ($cr->getTables() as $tab) {
                                    array_push($ut, $tab->getId());
                                }
                            }
                        }
                    }
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
                  $rev = new Reservation();
                    $rev->setRestaurant($res);
                 $rev->setDate(date_create($date));
                  $rev->setHeureArrive(date_create($ha));
                 $h = date_create($heureDepart);
                $rev->setHeureDepart($h);
                $rev->setNbPersonne($nb);


                $ftables = array();
                for ($j = 0; $j < sizeof($freeTables); $j++) {
                    $tab = array(
                        "id" => $freeTables[$j]->getId(),
                        "posX" => $freeTables[$j]->getPosX(),
                        "posY" => $freeTables[$j]->getPosY(),
                        "nbPalces" => $freeTables[$j]->getNbPalces(),
                        "numero" => $freeTables[$j]->getNumero(),
                        "restaurant_id" => $freeTables[$j]->getRestaurantId()->getId(),
                    );
                    $ftables[$j] = $tab;
                }


                return $this->json(array('error' => false
                ,'freeTables' => $ftables));
            } catch (\Exception $e) {
                print($e);
                return $this->json(array('error' => true));
            }
        }




    /**
     * @Route("/mobile/create-rev/{rid}/{date}/{ha}/{d}/{nb}/{client}/{tabs}", name="mobile_create_reservation", methods={"POST"})
     */

    public function createReservationMobile(Request $request, $rid,$date,$ha,$d,$nb,$client,$tabs)
    {
        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res = $rep->find($rid);
        $users = $this->getDoctrine()->getRepository(Client::class);
        $user = $users->find($client);
        if ($res == null or $client ==null) {
            return $this->json(array('error' => true));
        }
        //setting the data of the new reservation object
        $newRev = new Reservation();
        $newRev->setRestaurant($res);
        $newRev->setDate(date_create($date));
        $newRev->setClientId($user);
        $newRev->setHeureArrive(date_create($ha));
        $heureDepart = date_create($ha);
        $heureDepart->add(new \DateInterval('PT' . $d . 'M'));
        $heureDepart = $heureDepart->format('H:i');
        $newRev->setHeureDepart(date_create($heureDepart));
        $newRev->setNbPersonne((int)$nb);
        $newRev->setStatut("En Attente");
        //finding and checking the selected tables
        $tabRep = $this->getDoctrine()->getRepository(Table::class);
        $selected = explode('-', $tabs);

        foreach ($selected as $s){
            $t = $tabRep->find($s);
            if($t ==null){
                return $this->json(array('error' => true));
            }
            $newRev->addTable($t);
        }

        if ($newRev->getTables()->count() == 0) {
            return $this->json(array('error' => true));
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($newRev);
        $em->flush();
        return $this->json(array('error' => false));
    }
}
