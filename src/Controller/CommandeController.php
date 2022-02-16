<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Restaurant;
use App\Entity\Table;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    /**
     * @Route("/admin/choose-restaurant", name="choose-restaurant")
     */
    public function choisirRestaurant(): Response
    {
        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res= $rep->findAll();
        return $this->render('commande/choose-restaurant.html.twig', [
            'res'=>$res
        ]);
    }
    /**
     * @Route("/admin/choose-commandes/{id}", name="choose-commandes")
     */
    public function afficherCommande($id): Response
    {
        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res = $rep->find($id);
            $commandes = $this->getDoctrine()->getRepository(Commande::class)
                ->createQueryBuilder('t')
                ->where('t.restaurantId=?1')
                ->setParameter(1, $id)
                ->getQuery()
                ->getResult();

            return $this->render('commande/choose-commande.html.twig', [
                'res' => $id,
                'commandes' => $commandes

            ]);

    }

}