<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\RestaurantFavoris;
use App\Form\RestaurantType;
use App\Repository\RestaurantFavorisRepository;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Twilio\Rest\Client as Twilio ;


class RestaurantController extends AbstractController
{

//////////////////////////back end///////////////////////////////////////////////


    /**
     * @param RestaurantRepository $repository
     * @return Response
     * @Route ("/admin/restaurants",name="restaurantsAdmin")
     */
    public function AfficheRestaurantToAdmin(RestaurantRepository $repository)
    {
        // $repo=$this->getDoctrine()->getRepository(Restaurant::Res)
        $restaurant = $repository->findAll();
        return $this->render('restaurant/Affiche.html.twig',
            ['restaurant' => $restaurant]);

    }


    /**
     * @param Request $request
     * @return Response
     * @Route ("/admin/restaurants/add",name="restaurant_add")
     */

    function addRestaurant(Request $request)
    {
        $restaurant = new restaurant();
        $form = $this->createForm(RestaurantType::class, $restaurant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sid = "AC7d8f1b1f469b7d1ee91b92f8a8399252"; // Your Account SID from www.twilio.com/console
            $token = "45218b75b5d116ef0ece55369f76c703"; // Your Auth Token from www.twilio.com/console

            $client = new Twilio($sid, $token);
            $message = $client->messages->create(
                '+216'.$restaurant->getTelephone(), // Text this number
                [
                    'from' => '+14015616050', // From a valid Twilio number
                    'body' => 'Félicitations! Votre restaurant a ete cree! BON COURAGE'
                ]
            );
            if ($restaurant->getAdresse()) {
                $adresseGps = str_replace(" ", "+", $restaurant->getAdresse());
            }

            if ($restaurant->getImages() == "")
                $restaurant->setImages("no_images.jpg");
            else {
                $images = array();
                foreach ($restaurant->getImages() as $x) {
                    $file = new File($x);
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                    $file->move($this->getParameter('upload_directory'), $fileName);

                    array_push($images, $fileName);


                }

                $restaurant->setImages($images);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($restaurant);
            $em->flush();
            return $this->redirectToRoute('restaurantsAdmin');
        }
        return $this->render('restaurant/Add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/restaurants/{id}/update",name="update")
     */

    function updateRestaurants(RestaurantRepository $repository, $id, Request $request)
    {
        $restaurant = $repository->find($id);
        $name = $restaurant->getImages();
        $form = $this->createForm(RestaurantType::class, $restaurant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($restaurant->getImages() == "")
                $restaurant->setImages("no_images.jpg");
            else {
                $images = array();
                foreach ($restaurant->getImages() as $x) {
                    $file = new File($x);
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                    $file->move($this->getParameter('upload_directory'), $fileName);
                    array_push($images, $fileName);
                }
                $restaurant->setImages($images);
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('restaurantsAdmin');
        }
        return $this->render('restaurant/Update.html.twig', [
            'f' => $form->createView()
        ]);

    }
    /**
     * @Route("/admin/restaurants/{id}", name="restaurant_show" , methods={"GET"})
     */
    public function showRestaurant(Restaurant $restaurant): Response
    {
        return $this->render('restaurant/show.html.twig', [
            'restaurant' => $restaurant,
        ]);
    }


    /**
     * @Route ("/admin/restaurants/{id}/del",name="del")
     */
    function deleteRestaurant($id, RestaurantRepository $repository)
    {
        $restaurant = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($restaurant);
        $em->flush();
        return $this->redirectToRoute("restaurantsAdmin");
    }

///////////////////////front office///////////////////////////////

    /**
     * @param RestaurantRepository $repository
     * @return Response
     * @Route ("/restaurants",name="restaurantsClient" )
     */

    public function afficheRestaurantToClient(RestaurantRepository $repository)
    {
        // $repo=$this->getDoctrine()->getRepository(Restaurant::Res)
        $restaurant = $repository->findAll();
        return $this->render('restaurant/client/index.html.twig',
            ['restaurant' => $restaurant,]);

    }

    /**
     * @Route("restaurant/{id}", name="restaurant_showC", methods={"GET"})
     */
    public function showclient(Restaurant $restaurant,SessionInterface $session,$id): Response
    {
        $panier = $session->set("res",$id);

        return $this->render('restaurant/client/AfficheRC.html.twig', [
            'restaurant' => $restaurant,
        ]);
    }
    /**
     * @Route ("restaurant/{id}/like" , name="restaurant_like")
     *
     *
     * @param Restaurant $resto
     *
     * @param RestaurantFavorisRepository $likerepo
     * @return Response
     */
    public function like(Restaurant $resto, RestaurantFavorisRepository $likerepo): Response


    {
        $manager = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        if (!$user) return $this->json(['code' => 403, 'message' => 'il faut connecter'], 403);

        if ($resto->isLikedByUser($user)) {
            $like = $likerepo->findOneBy([
                    'Restaurant' => $resto,
                    'client' => $user ]

            );

            $manager->remove($like);
            $manager->flush();
            return $this->json(['code' => 200, 'message' => 'supprimé', 'favoris' => $likerepo->count(['Restaurant' => $resto])], 200);


        }
        else {
            $like = new RestaurantFavoris();
            $like->setRestaurant($resto)
                ->setClient($user);
            $manager->persist($like);
            $manager->flush();

            return $this->json(['code' => 200, 'message' => 'ajouutéééééé', 'favoris' => $likerepo->count(['Restaurant' => $resto])], 200);
        }}


}
