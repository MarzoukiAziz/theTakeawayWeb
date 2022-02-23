<?php

namespace App\Controller;

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
     * @Route("/ingrediant", name="ingrediant")
     */
    public function index(): Response
    {
        return $this->render('ingrediant/index.html.twig', [
            'controller_name' => 'IngrediantController',
        ]);
    }

/**
 * @Route("/addingrediant", name="addingrediant")
 */
public function addingrediant(Request $request)

{
    $fr = new Ingrediant();
    $form = $this->createForm(IngrediantType::class,$fr);

    $form->handleRequest($request);
    if ($form->isSubmitted() ) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($fr);
        $em->flush();
        return $this->redirectToRoute('listingrediant');
    }
    return $this->render("/ingrediant/ajouter-ingrediant.html.twig",array('f'=>$form->createView()));
}

/**
 * @Route("/listingrediant", name="listingrediant")
 */
public function listingrediant()
{
    $fr = $this->getDoctrine()->getRepository(Ingrediant::class)->findAll();
    return $this->render('ingrediant/affiche-ingrediant.html.twig', array("fr" => $fr));
}




/**
 * @Route("/deleteingrediant/{id}", name="deleteingrediant")
 */
public function delete($id)
{
    $f = $this->getDoctrine()->getRepository(Ingrediant::class)->find($id);
    $em = $this->getDoctrine()->getManager();
    $em->remove($f);
    $em->flush();
    return $this->redirectToRoute("listingrediant");
}

/**
 * @Route("/updateingrediant/{id}", name="updateingrediant")
 */
public function updateingrediant(Request $request,$id)
{
    $fr = $this->getDoctrine()->getRepository(Ingrediant::class)->find($id);
    $form = $this->createForm(IngrediantType::class, $fr);
    $form->add('modifier',SubmitType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('listingrediant');
    }
    return $this->render("ingrediant/update-ingrediant.html.twig",array('form'=>$form->createView()));
}


}