<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\Ingrediant;
use App\Form\IngrediantType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class IngrediantController extends AbstractController
{

    /**
     * @Route("/admin/ingrediant/", name="ingrediant-resto")
     */
    public function choisirRestaurant(): Response
    {
        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res = $rep->findAll();
        return $this->render('ingrediant/choose-restaurant.html.twig', [
            'res'=>$res,
        ]);
    }

    /**
     * @Route("/admin/ingrediant/{rid}", name="listingrediant")
     */
    public function listingrediant($rid)
    {
        $ing = $this->getDoctrine()->getRepository(Ingrediant::class)
            ->createQueryBuilder('r')
            ->where('r.restaurant=?1')
            ->setParameter(1, $rid)
            ->getQuery()
            ->getResult();
        return $this->render('ingrediant/affiche-ingrediant.html.twig', array("fr" => $ing,"rid"=>$rid));
    }



    /**
 * @Route("/admin/Ingrediant/{id}/add", name="addingrediant")
 */
public function addingrediant(Request $request,$id)

{
    $fr = new Ingrediant();
    $form = $this->createForm(IngrediantType::class,$fr);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $res = $this->getDoctrine()->getRepository(Restaurant::class)->find($id);
        $fr->setRestaurant($res);

        $em = $this->getDoctrine()->getManager();
        $em->persist($fr);
        $em->flush();
        return $this->redirectToRoute('listingrediant',["rid"=>$id]);
    }
    return $this->render("/ingrediant/ajouter-ingrediant.html.twig",array('f'=>$form->createView()));
}





/**
 * @Route("/admin/Ingrediant/{rid}/{iid}/delete", name="deleteingrediant")
 */
public function delete($rid,$iid)
{
    $f = $this->getDoctrine()->getRepository(Ingrediant::class)->find($iid);
    $em = $this->getDoctrine()->getManager();
    $em->remove($f);
    $em->flush();
    return $this->redirectToRoute("listingrediant",["rid"=>$rid]);
}

/**
 * @Route("/admin/Ingrediant/{rid}/{iid}/update", name="updateingrediant")
 */
public function updateingrediant(Request $request,$rid,$iid)
{
    $fr = $this->getDoctrine()->getRepository(Ingrediant::class)->find($iid);
    $fr->getQuantite();
    $form = $this->createForm(IngrediantType::class, $fr);
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush();
       $q= $fr->getQuantite();
       $nom=$fr->getNom();
       if ($q==0)
       {

        $this->addFlash('danger',"Oups !! la $nom nest plus disponible");}
        return $this->redirectToRoute('listingrediant',["rid"=>$rid]);
    }
    return $this->render("ingrediant/update-ingrediant.html.twig",array('f'=>$form->createView()));
}


}