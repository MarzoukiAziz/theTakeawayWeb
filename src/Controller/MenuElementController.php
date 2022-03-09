<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
<<<<<<< Updated upstream
=======
use App\Entity\ElementDetails;
>>>>>>> Stashed changes
use App\Entity\MenuElement;
use App\Entity\Restaurant;
use App\Form\CommandeType;
use App\Repository\MenuElementRepository;
<<<<<<< Updated upstream
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Session;
use phpDocumentor\Reflection\Element;
=======
use App\services\QrcodeService;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Session;
use phpDocumentor\Reflection\Element;
use phpDocumentor\Reflection\Types\Array_;
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
    public function panier(SessionInterface $session, MenuElementRepository $menuElementRepository,Request $request,EntityManagerInterface $entityManager): Response
    {
=======
    public function panier(SessionInterface $session, MenuElementRepository $menuElementRepository,Request $request,EntityManagerInterface $entityManager, QrcodeService $qrcodeService): Response
    {
        $Qrcode =null;
>>>>>>> Stashed changes
        $panier =$session->get("panier",[]);
        //on fabrique les données
        $dataPanier =[];
        $total = 0;
<<<<<<< Updated upstream

=======
        $commande = new Commande();
        $d=array();
>>>>>>> Stashed changes
        foreach ($panier as $id => $quantite){
            $menuElement = $menuElementRepository->find($id);
            $dataPanier[] = [
                "produit" => $menuElement,
                "quantite" => $quantite
            ];
<<<<<<< Updated upstream
            $total += $menuElement->getPrix() * $quantite;
        }

        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
=======
            $c = new ElementDetails();
            $c->setElementId($menuElement);
            $c->setQuantite($quantite);
            $c->setOptions("");
            $commande->addDetail($c);
            $c->setCommande($commande);
            array_push($d,$c);
            $total += $menuElement->getPrix() * $quantite;
        }


        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);
        //chnage 1 with the current restaurant from session data
>>>>>>> Stashed changes
        $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->find("1");
        if($restaurant == null){
            $this->redirectToRoute("front-erreur");
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $commande->setRestaurant($restaurant);
            //to change later
<<<<<<< Updated upstream
            $client = $this->getDoctrine()->getRepository(Client::class)->find("1");
            $commande->setClientId($client);
            $commande->setStatut("En Attente");
            $commande->setPrixTotal($total);

=======
            $client = $this->getDoctrine()->getRepository(Client::class)->find($this->getUser());
            $commande->setClientId($client);
            $commande->setStatut("En Attente");
            $commande->setPrixTotal($total);
>>>>>>> Stashed changes
            $date = new \DateTime();
            $commande->setDate($date);
            //par defaut success à traiter plus tard
            $commande->setStatutPaiement("Success");
<<<<<<< Updated upstream
=======
            foreach ($d as $x){
                $entityManager->persist($x);
            }
>>>>>>> Stashed changes
            $entityManager->persist($commande);
            $entityManager->flush();

            return $this->redirectToRoute('commandes', [], Response::HTTP_SEE_OTHER);
        }

<<<<<<< Updated upstream
=======
        $Qrcode=$qrcodeService->qrcode("la commande");
>>>>>>> Stashed changes

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
