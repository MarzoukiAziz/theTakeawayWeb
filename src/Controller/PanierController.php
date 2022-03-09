<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\ElementDetails;
use App\Entity\MenuElement;
use App\Entity\Restaurant;
use App\Form\CommandeType;
use App\Repository\MenuElementRepository;
use App\services\QrcodeService;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Session;
use phpDocumentor\Reflection\Element;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController   extends AbstractController
{
    ////////////front-office///////////////////////////
    /**
     * @Route("/panier", name="Panier_element")
     */
    public function panier(SessionInterface $session, MenuElementRepository $menuElementRepository,Request $request,EntityManagerInterface $entityManager, QrcodeService $qrcodeService): Response
    {
        $Qrcode =null;
        $panier =$session->get("panier",[]);
        //on fabrique les données
        $dataPanier =[];
        $total = 0;
        $commande = new Commande();
        $d=array();
        foreach ($panier as $id => $quantite){
            $menuElement = $menuElementRepository->find($id);
            $dataPanier[] = [
                "produit" => $menuElement,
                "quantite" => $quantite
            ];
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
        $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->find($session->get("res"));
        if($restaurant == null){
            $this->redirectToRoute("front-erreur");
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $commande->setRestaurant($restaurant);
            //to change later
            $client = $this->getDoctrine()->getRepository(Client::class)->find($this->getUser());
            $commande->setClientId($client);
            $commande->setStatut("En attente");
            $commande->setPrixTotal($total);
            $date = new \DateTime();
            $commande->setDate($date);
            //par defaut success à traiter plus tard
            $commande->setStatutPaiement("Success");
            foreach ($d as $x){
                $entityManager->persist($x);
            }
            $session->set("panier", []);
            $entityManager->persist($commande);
            $entityManager->flush();
            $Qrcode=$qrcodeService->qrcode($commande->toString(),$commande->getId());

            return $this->redirectToRoute('commandes', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('panier/panier.html.twig',[  'commande' => $commande,
            'form' => $form->createView(),"dataPanier"=>$dataPanier,"total"=>$total]);

    }

    /**
     * @Route("/panier/add/{id}", name="cart_add")
     */
    public function add(Request $request,MenuElement $menuElement, SessionInterface $session)
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
        $this->addFlash("success","Element ajouté au Panier!");
        //return $this->redirectToRoute("Panier_element");
        return $this->redirectToRoute("menuClient");

    }

    /**
     * @Route("/panier/add-from-panier/{id}", name="cart_add_panier")
     */
    public function addFromPanier(Request $request,MenuElement $menuElement, SessionInterface $session)
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
     * @Route("/panier/remove/{id}", name="cart_remove")
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
     * @Route("/panier/delete/{id}", name="cart_delete")
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
     * @Route("/panier/delete-all", name="deleteAll")
     */
    public function deleteAll(SessionInterface $session)
    {

        $session->set("panier", []);
        //on sauvgarde la session
        return $this->redirectToRoute("Panier_element");
    }
}
