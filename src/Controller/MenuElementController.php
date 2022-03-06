<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\MenuElement;
use App\Entity\Restaurant;
use App\Form\CommandeType;
use App\Repository\MenuElementRepository;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Session;
use phpDocumentor\Reflection\Element;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class MenuElementController extends AbstractController
{





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
     * @Route("/panier", name="Panier_element")
     */
    public function panier(SessionInterface $session, MenuElementRepository $menuElementRepository,Request $request,EntityManagerInterface $entityManager): Response
    {
        $panier =$session->get("panier",[]);
        //on fabrique les données
        $dataPanier =[];
        $total = 0;

        foreach ($panier as $id => $quantite){
            $menuElement = $menuElementRepository->find($id);
            $dataPanier[] = [
                "produit" => $menuElement,
                "quantite" => $quantite
            ];
            $total += $menuElement->getPrix() * $quantite;
        }

        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
        $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->find("1");
        if($restaurant == null){
            $this->redirectToRoute("front-erreur");
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $commande->setRestaurant($restaurant);
            //to change later
            $client = $this->getDoctrine()->getRepository(Client::class)->find("1");
            $commande->setClientId($client);
            $commande->setStatut("En Attente");
            $commande->setPrixTotal($total);

            $date = new \DateTime();
            $commande->setDate($date);
            //par defaut success à traiter plus tard
            $commande->setStatutPaiement("Success");
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('commandes', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('menu_element/panier.html.twig',[  'commande' => $commande,
            'form' => $form->createView(),"dataPanier"=>$dataPanier,"total"=>$total]);

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
    /**
     * @Route("/remove/{id}", name="cart_remove")
     */
    public function remove(MenuElement $menuElement, SessionInterface $session)
    {
        $panier = $session->get("panier", []);
        $id = $menuElement->getId();

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else {
               unset($panier[$id]);
            }
        }

        //on sauvgarde la session
        $session->set("panier",$panier);
        return $this->redirectToRoute("Panier_element");
    }

    /**
     * @Route("/delete/{id}", name="cart_delete")
     */
    public function delete(MenuElement $menuElement, SessionInterface $session)
    {
        $panier = $session->get("panier", []);
        $id = $menuElement->getId();

        if(!empty($panier[$id])){
                unset($panier[$id]);
            }

        //on sauvgarde la session
        $session->set("panier",$panier);
        return $this->redirectToRoute("Panier_element");
    }

    /**
     * @Route("/delete", name="deleteAll")
     */
    public function deleteAll(SessionInterface $session)
    {

        $session->set("panier", []);
        //on sauvgarde la session
        return $this->redirectToRoute("Panier_element");
    }
}
