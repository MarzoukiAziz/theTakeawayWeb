<?php

namespace App\Controller;

use App\Repository\MenuElementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenufrontController extends AbstractController
{
    /**
     * @Route("/menufront", name="menufront")
     */
    public function index(MenuElementRepository $menuElementRepository): Response
    {
        return $this->render('menufront/index.html.twig', [
            'menu_elements' => $menuElementRepository->findAll(),
        ]);
    }
}
