<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SouhaitsController extends AbstractController
{
    /**
     * @Route("/wishlist", name="wishlist")
     */
    public function wishlist()
    {
        $res=array();
        foreach($this->getUser()->getFavoris() as $f){
            array_push($res,$f->getRestaurant());
        }
        return $this->render('wishlist/index.html.twig', array("restaurant" => $res));
    }
}
