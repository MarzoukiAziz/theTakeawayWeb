<?php

namespace App\Controller;

use App\Entity\ElementDetails;
use App\Form\ElementDetailsType;
use App\Repository\ElementDetailsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/element/details")
 */
class ElementDetailsController extends AbstractController
{
    /**
     * @Route("/", name="element_details_index", methods={"GET"})
     */
    public function index(ElementDetailsRepository $elementDetailsRepository): Response
    {
        return $this->render('element_details/index.html.twig', [
            'element_details' => $elementDetailsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="element_details_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $elementDetail = new ElementDetails();
        $form = $this->createForm(ElementDetailsType::class, $elementDetail);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            //for test only
            $elementDetail->setElementId("1");
            $elementDetail->setCommande("1");
            $entityManager->persist($elementDetail);
            $entityManager->flush();

            return $this->redirectToRoute('element_details_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('element_details/new.html.twig', [
            'element_detail' => $elementDetail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="element_details_show", methods={"GET"})
     */
    public function show(ElementDetails $elementDetail): Response
    {
        return $this->render('element_details/show.html.twig', [
            'element_detail' => $elementDetail,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="element_details_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ElementDetails $elementDetail, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ElementDetailsType::class, $elementDetail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('element_details_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('element_details/edit.html.twig', [
            'element_detail' => $elementDetail,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="element_details_delete", methods={"POST"})
     */
    public function delete(Request $request, ElementDetails $elementDetail, EntityManagerInterface $entityManager): Response
    {
        $cid=$elementDetail->getCommande()->getId();
        if ($this->isCsrfTokenValid('delete'.$elementDetail->getId(), $request->request->get('_token'))) {
            $entityManager->remove($elementDetail);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commande-show-admin', ['cid'=>$cid], Response::HTTP_SEE_OTHER);
    }
}
