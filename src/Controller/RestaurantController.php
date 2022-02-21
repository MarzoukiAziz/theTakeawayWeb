<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route ("/Affiche",name="AfficheR")
     */

    public function Affiche(RestaurantRepository $repository){
        // $repo=$this->getDoctrine()->getRepository(Restaurant::Res)
        $restaurant=$repository->findAll();
        return $this->render('restaurant/Affiche.html.twig',
            ['restaurant'=>$restaurant]);

    }

    /**
     * @Route ("/supp/{id}",name="del")
     */
    function Delete($id,RestaurantRepository $repository){
        $restaurant=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($restaurant);
        $em->flush();
        return $this->redirectToRoute("AfficheR");
    }

}
