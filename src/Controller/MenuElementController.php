<?php

namespace App\Controller;

use App\Entity\MenuElement;
use App\Repository\MenuElementRepository;
use mysql_xdevapi\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MenuElementController extends AbstractController
{


    /**
     * @Route("/panier", name="Panier_element")
     */
    public function panier(SessionInterface $session, MenuElementRepository $menuElementRepository): Response
    {
        $panier =$session->get("panier",[]);
        //on fabrique les données
        $dataPanier =[];
        $total = 0;

        foreach ($panier as $id => $quantite){
            $MenuElement = $menuElementRepository->find($id);
            $dataPanier = [
                "produit" => $MenuElement,
                "quantite" => $quantite
            ];
            $total += $MenuElement->getPrix() * $quantite;
        }

        return $this->render('menu_element/panier.html.twig', compact("dataPanier","total"));

    }


    /**
     * @Route("/menu/element", name="menu_element")
     */
    public function index(): Response
    {
        $rep = $this->getDoctrine()->getRepository(MenuElement::class);
        $menu = $rep->findAll();

        return $this->render('menu_element/index.html.twig', [
            'controller_name' => 'MenuElementController',
            'menu' => $menu,
        ]);

    }

    /**
     * @Route("/add/{id}", name="cart_add")
     */
    public function add(MenuElement $menuElement, SessionInterface $session)
    {
        $panier = $session->get("panier", []);
        $id = $menuElement->getId();
        if(!empty($panier[$id])){
            $panier[$id]++;
        }
        else{
            $panier[$id]= 1 ;
        }
        //on sauvgarde la session
        $session->set("panier",$panier);
        return $this->redirectToRoute("Panier_element");
    }
}
