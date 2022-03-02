<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Client;

use App\Entity\RestaurantFavoris;
use App\Form\RestaurantType;
use App\Repository\RestaurantFavorisRepository;
use App\Repository\RestaurantRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;


class RestaurantController extends AbstractController
{
    /**
     * @Route("/restaurant", name="restaurant")
     */
    public function index(): Response
    {
        return $this->render('restaurant/index.html.twig', [
            'controller_name' => 'RestaurantController',
        ]);
    }

    /**
     * @param RestaurantRepository $repository
     * @return Response
     * @Route ("/AfficheClient",name="AfficheClient" )
     */

    public function AfficheClient(RestaurantRepository $repository)
    {
        // $repo=$this->getDoctrine()->getRepository(Restaurant::Res)
        $restaurant = $repository->findAll();
        return $this->render('restaurant/client/index.html.twig',
            ['restaurant' => $restaurant,]);

    }

    /**
     * @param RestaurantRepository $repository
     * @return Response
     * @Route ("/Affiche",name="AfficheR")
     */
    public function Affiche(RestaurantRepository $repository)
    {
        // $repo=$this->getDoctrine()->getRepository(Restaurant::Res)
        $restaurant = $repository->findAll();
        return $this->render('restaurant/Affiche.html.twig',
            ['restaurant' => $restaurant]);

    }

    /**
     * @Route("/{id}", name="restaurant_showC", methods={"GET"})
     */
    public function showclient(Restaurant $restaurant): Response
    {
        return $this->render('restaurant/client/AfficheRC.html.twig', [
            'restaurant' => $restaurant,
        ]);
    }


    /**
     * @Route("/admin/{id}", name="restaurant_show" , methods={"GET"})
     */
    public function show(Restaurant $restaurant): Response
    {
        return $this->render('restaurant/show.html.twig', [
            'restaurant' => $restaurant,
        ]);
    }


    /**
     * @Route ("/supp/{id}",name="del")
     */
    function Delete($id, RestaurantRepository $repository)
    {
        $restaurant = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($restaurant);
        $em->flush();
        return $this->redirectToRoute("AfficheR");
    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("restaurant/Add",name="restaurant_add")
     */

    function Add(Request $request)
    {
        $restaurant = new restaurant();
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
            $em->persist($restaurant);
            $em->flush();
            return $this->redirectToRoute('AfficheR');
        }
        return $this->render('restaurant/Add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("restaurant/Update/{id}",name="update")
     */

    function Update(RestaurantRepository $repository, $id, Request $request)
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
            return $this->redirectToRoute('AfficheR');
        }
        return $this->render('restaurant/Update.html.twig', [
            'f' => $form->createView()
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
            return $this->json(['code' => 200, 'message' => 'yedek', 'favoris' => $likerepo->count(['Restaurant' => $resto])], 200);


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