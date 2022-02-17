<?php

namespace App\Controller;

use App\Entity\CartBancaire;
use App\Form\CartBancaireType;
use App\Repository\CartBancaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CartBancaireController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_bancaire_index", methods={"GET"})
     */
    public function index(CartBancaireRepository $cartBancaireRepository): Response
    {
        return $this->render('cart_bancaire/index.html.twig', [
            'cart_bancaires' => $cartBancaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("cart/new", name="cart_bancaire_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cartBancaire = new CartBancaire();
        $form = $this->createForm(CartBancaireType::class, $cartBancaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cartBancaire);
            $entityManager->flush();

            return $this->redirectToRoute('cart_bancaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cart_bancaire/new.html.twig', [
            'cart_bancaire' => $cartBancaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("cart/{id}", name="cart_bancaire_show", methods={"GET"})
     */
    public function show(CartBancaire $cartBancaire): Response
    {
        return $this->render('cart_bancaire/show.html.twig', [
            'cart_bancaire' => $cartBancaire,
        ]);
    }

    /**
     * @Route("cart/{id}/edit", name="cart_bancaire_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CartBancaire $cartBancaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CartBancaireType::class, $cartBancaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('cart_bancaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cart_bancaire/edit.html.twig', [
            'cart_bancaire' => $cartBancaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("cart/{id}", name="cart_bancaire_delete", methods={"POST"})
     */
    public function delete(Request $request, CartBancaire $cartBancaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cartBancaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cartBancaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cart_bancaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
