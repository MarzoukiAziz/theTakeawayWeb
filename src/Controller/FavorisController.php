<?php

namespace App\Controller;

use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavorisController extends AbstractController
{
    /**
     * @Route("/favoris/{id}", name="app_favoris")
     */
    public function index($id): Response
    {
        $fr = $this->getDoctrine()->getRepository(Client::class)->find($id);
        return $this->render('Restaurant/wishlist.html.twig', array("fr" => $fr));
    }
}
