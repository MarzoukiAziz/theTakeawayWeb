<?php

namespace App\Controller;

use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PromofrontController extends AbstractController
{
    /**
     * @Route("/promofront", name="promofront")
     */
    public function index(PromotionRepository $promotionRepository): Response
    {
        return $this->render('promofront/index.html.twig', [
            'promotions' => $promotionRepository->findAll(),
        ]);
    }
}
