<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CartBancaireRepository;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test",methods={"GET"})
     */
    public function index(CartBancaireRepository $cartBancaireRepository): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'cart_bancaires' => $cartBancaireRepository->findAll()
        ]);
    }
}
