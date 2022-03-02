<?php

namespace App\Controller;

use App\Entity\Table;
use App\Entity\Restaurant;

use App\Form\TableType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class
TableController extends AbstractController
{
    /**
     * @Route("/admin/tables", name="admin-tables-choose-restaurant")
     */
    public function choisirRestaurant(): Response
    {
        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res = $rep->findAll();
        return $this->render('table/admin/admin-choose-restaurant.html.twig', [
            'res'=>$res,
        ]);
    }


    /**
     * @Route("/admin/tables/{id}", name="admin-tables-restaurant")
     */
    public function afficherTables(Request $request,$id, PaginatorInterface $paginator): Response
    {
        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res = $rep->find($id);
        if($res){
            $tables = $this->getDoctrine()->getRepository(Table::class)
                ->createQueryBuilder('t')
                ->where('t.restaurantId=?1')
                ->setParameter(1, $id)
                ->getQuery()
                ->getResult();
            $tables=$paginator->paginate(
                $tables,
                $request->query->getInt('page', 1),
                10
            );
        return $this->render('table/admin/admin-tables.html.twig', [
            'tables'=>$tables,
            'res'=>$res
        ]);
        }
        return $this->render('back-erreur.html.twig');
    }


    /**
        * @Route("/admin/tables/{id}/add", name="admin-add-table")
     */
    public function ajouterTable(Request $request,$id): Response
    {
        $table = new Table();
        $rep = $this->getDoctrine()->getRepository(Restaurant::class);
        $res = $rep->find($id);
        $table->setRestaurantId($res);
        $form = $this->createForm(TableType::class,$table);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($table);
            $em->flush();
            return $this->redirectToRoute("admin-tables-restaurant",['id'=>$id]);
        }
        return $this->render("table/admin/admin-table-add.html.twig",[
            'f'=>$form->createView(),'res_nom'=>$res->getNom()]);
    }

    /**
     * @Route("/admin/tables/{rid}/{id}/update", name="admin-update-table")
     */

    public function majTable(Request $request,$rid, $id )
    {
        $em= $this->getDoctrine()->getManager();
        $tab= $em ->getRepository (Table::class)->find($id);
        $form =$this->createForm (TableType::class, $tab);
        $form ->handleRequest($request);
        if ($form->isSubmitted() && $form-> isValid ())
        {
            $em->flush();
            return $this->redirectToRoute("admin-tables-restaurant",['id'=>$rid]);
        }
        return $this->render('table/admin/admin-table-update.html.twig', [
            'f' => $form -> createView(),
        ]);
    }

    /**
     * @Route("/admin/tables/{rid}/{id}/delete", name="admin-delete-table")
     */

    public function supprimerTable($rid, $id )
    {
        $em= $this->getDoctrine()->getManager();
        $tab= $em ->getRepository (Table::class)->find($id);
        $em-> remove ($tab);
        $em->flush();
        return $this->redirectToRoute("admin-tables-restaurant",['id'=>$rid]);
    }
}
