<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Client;
use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReclamationType;
use App\Form\ReponseType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class ReclamationController extends AbstractController
{
    /*****************************
     *****BACK OFFICE************
     ****************************/


    /**
     * @Route("/admin/reclamations", name="reclamation_admin", methods={"GET"})
     */
    public function showReclamationsToAdmin(Request $request, PaginatorInterface $paginator, ReclamationRepository $reclamationRepository): Response
    {
        //warning find by client id
        $reclamations = $paginator->paginate(
            $reclamationRepository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('reclamation/admin/index.html.twig', [
            'reclamations' => $reclamations
        ]);
    }


    /**
     * @Route("/admin/reclamations/{id}", name="reclamation_show_admin", methods={"GET","POST"})
     */
    public function showReclamationToAdmin(Request $request, Reclamation $reclamation): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        $reponses = $this->getDoctrine()->getRepository(Reponse::class)
            ->createQueryBuilder('r')
            ->andWhere('r.reclamation=?1')
            ->setParameter(1, $reclamation)
            ->getQuery()
            ->getResult();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->getRepository(Reponse::class);
            ///Warning
            /// change this later

            $author = $this->getDoctrine()->getRepository(Client::class)->find("2");
            $reponse->setAuthor($author);
            $date = new \DateTime();
            $reponse->setDate($date);
            $reponse->setHeure($date);
            $reponse->setReclamation($reclamation);
            $em->persist($reponse);
            $em->flush();

            return $this->redirectToRoute('reclamation_show_admin', ['id' => $reclamation->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('reclamation/admin/show.html.twig', [
            'reclamation' => $reclamation,
            'reponses' => $reponses,
            'f' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/reclamations/{id}/delete", name="reclamation_delete_admin", methods={"POST"})
     */
    public function deleteReclamtionByAdmin(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $em = $this->getDoctrine()->getManager();
        $rec = $em->getRepository(Reclamation::class)->find($id);
        $em->remove($rec);
        $em->flush();
        return $this->redirectToRoute('reclamation_admin', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/admin/reclamations/{cid}/close", name="admin-close-reclamation" , methods={"POST"})
     */
    //change the status of a reservation
    public function closeReclamation($cid): Response
    {
        $rep = $this->getDoctrine()->getRepository(Reclamation::class);
        $reclamation = $rep->find($cid);
        //check if the parameters are correct
        if ($reclamation == null) {
            return $this->redirectToRoute("erreur-back");
        }
        $reclamation->setStatut("Fermé");
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('reclamation_show_admin', ['id' => $reclamation->getId()]);
    }

    /**
     * @Route("/admin/reclamations/{cid}/reopen", name="admin-reopen-reclamation" , methods={"POST"})
     */
    //change the status of a reservation
    public function reopenReclamation($cid): Response
    {
        $rep = $this->getDoctrine()->getRepository(Reclamation::class);
        $reclamation = $rep->find($cid);
        //check if the parameters are correct
        if ($reclamation == null) {
            return $this->redirectToRoute("erreur-back");
        }
        $reclamation->setStatut("Ouvert");
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('reclamation_show_admin', ['id' => $reclamation->getId()]);
    }

    /*****************************
     *****FRONT OFFICE************
     ****************************/


    /**
     * @Route("/reclamations", name="reclamation_index", methods={"GET"})
     */
    public function index(): Response
    {
        $client = $this->getDoctrine()->getRepository(Client::class)->find(1);
        $reclamations = $this->getDoctrine()->getRepository(Reclamation::class)
            ->createQueryBuilder('r')
            ->andWhere('r.clientId=?1')
            ->setParameter(1, $client->getId())
            ->getQuery()
            ->getResult();

        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamations
        ]);
    }

    /**
     * @Route("/reclamations/new", name="reclamation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setStatut("Ouvert");
            ///Warning
            /// change this later

            $client = $this->getDoctrine()->getRepository(Client::class)->find("1");
            $reclamation->setClientId($client);
            $date = new \DateTime();
            $reclamation->setDate($date);
            $reclamation->setHeure($date);
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'f' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reclamations/{id}", name="reclamation_show", methods={"GET","POST"})
     */
    public function show(Request $request, Reclamation $reclamation): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        $reponses = $this->getDoctrine()->getRepository(Reponse::class)
            ->createQueryBuilder('r')
            ->andWhere('r.reclamation=?1')
            ->setParameter(1, $reclamation)
            ->getQuery()
            ->getResult();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->getRepository(Reponse::class);
            ///Warning
            /// change this later

            $client = $this->getDoctrine()->getRepository(Client::class)->find("1");
            $reponse->setAuthor($client);
            $date = new \DateTime();
            $reponse->setDate($date);
            $reponse->setHeure($date);
            $reponse->setReclamation($reclamation);
            $em->persist($reponse);
            $em->flush();

            return $this->redirectToRoute('reclamation_show', ['id' => $reclamation->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
            'reponses' => $reponses,
            'f' => $form->createView(),
        ]);


    }

    /**
     * @Route("/reclamations/{id}/delete", name="reclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        $em = $this->getDoctrine()->getManager();
        $rec = $em->getRepository(Reclamation::class)->find($id);
        $em->remove($rec);
        $em->flush();
        return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}
