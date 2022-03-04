<?php

namespace App\Controller;

use App\Entity\MenuElement;
use App\Form\MenuElementType;
use App\Repository\MenuElementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;


/**
 * @Route("/menu")
 */


class MenuController extends AbstractController
{




    /**
     * @Route("/", name="menu_index", methods={"GET"})
     */
    public function index(MenuElementRepository $menuElementRepository): Response
    {
        return $this->render('menu/index.html.twig', [
            'menu_elements' => $menuElementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="menu_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $menuElement = new MenuElement();
        $form = $this->createForm(MenuElementType::class, $menuElement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($menuElement->getImage()=="")
                $menuElement->setImage("no_image.jpg");
            else
            {
                $file =new File($menuElement->getImage());
                $fileName=md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'),$fileName);
                $menuElement->setImage($fileName);

            }







            $entityManager->persist($menuElement);
            $entityManager->flush();

            return $this->redirectToRoute('menu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('menu/new.html.twig', [
            'menu_element' => $menuElement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="menu_show", methods={"GET"})
     */
    public function show(MenuElement $menuElement): Response
    {
        return $this->render('menu/show.html.twig', [
            'menu_element' => $menuElement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="menu_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, MenuElement $menuElement, EntityManagerInterface $entityManager): Response
    {
        $name= $menuElement->getImage();
        $form = $this->createForm(MenuElementType::class, $menuElement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($menuElement->getImage()=="")
                $menuElement->setImage($name);
            else
            {
                $file = new File($menuElement->getImage());
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'),$fileName);
                $menuElement->setImage($fileName);
                if($name!="no_image.jpg")
                if( file_exists("couvertures/".$name))
                    unlink("couvertures/".$name) ;




            }
            
            
            
            $entityManager->flush();

            return $this->redirectToRoute('menu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('menu/edit.html.twig', [
            'menu_element' => $menuElement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="menu_delete", methods={"POST"})
     */
    public function delete(Request $request, MenuElement $menuElement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$menuElement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($menuElement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('menu_index', [], Response::HTTP_SEE_OTHER);
    }
}
