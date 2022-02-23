<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
        ]);
    }
    /**
     * @Route("/error", name="erreur-front")
     */
    public function errorFront(): Response
    {
        return $this->render('front-erreur.html.twig', [
        ]);
    }
    /**
     * @Route("/admin/error", name="erreur-back")
     */
    public function errorBack(): Response
    {
        return $this->render('back-erreur.html.twig', [
        ]);
    }
}
