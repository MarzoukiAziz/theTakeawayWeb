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


class MenuController extends AbstractController
{

    /**
     * @Route("/menu/", name="menuClient")
     */
    public function menuClient(MenuElementRepository $menuElementRepository): Response
    {
        return $this->render('menu/client/index.html.twig', [
            'menu_elements' => $menuElementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/menu", name="menu_index", methods={"GET"})
     */
    public function index(MenuElementRepository $menuElementRepository): Response
    {
        return $this->render('menu/index.html.twig', [
            'menu_elements' => $menuElementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/menu/new", name="menu_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $menuElement = new MenuElement();
        $form = $this->createForm(MenuElementType::class, $menuElement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($menuElement->getImage() == "")
                $menuElement->setImage("no_image.jpg");
            else {
                $file = new File($menuElement->getImage());
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $fileName);
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
     * @Route("/admin/menu/{id}", name="menu_show", methods={"GET"})
     */
    public function show(MenuElement $menuElement): Response
    {
        return $this->render('menu/show.html.twig', [
            'menu_element' => $menuElement,
        ]);
    }

    /**
     * @Route("/admin/menu/{id}/edit", name="menu_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, MenuElement $menuElement, EntityManagerInterface $entityManager): Response
    {
        $name = $menuElement->getImage();
        $form = $this->createForm(MenuElementType::class, $menuElement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($menuElement->getImage() == "")
                $menuElement->setImage($name);
            else {
                $file = new File($menuElement->getImage());
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $fileName);
                $menuElement->setImage($fileName);
                if ($name != "no_image.jpg")
                    if (file_exists("couvertures/" . $name))
                        unlink("couvertures/" . $name);
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
     * @Route("/admin/menu/{id}", name="menu_delete", methods={"POST"})
     */
    public function delete(Request $request, MenuElement $menuElement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $menuElement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($menuElement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('menu_index', [], Response::HTTP_SEE_OTHER);
    }

    /////MOBILE SERVICES/////

    /**
     * @Route("/mobile/menu/", name="mobile_menu", methods={"GET"})
     */
    public function mobileMenu(MenuElementRepository $menuElementRepository, request $request)
    {

        try {
            $menu = $menuElementRepository->findAll();

            $res = array();

            for ($i = 0; $i < sizeof($menu); $i++) {
                $data = array(
                    'id' => $menu[$i]->getId(),
                    'nom' => $menu[$i]->getNom(),
                    'categorie' => $menu[$i]->getCategorie(),
                    'image' => $menu[$i]->getImage(),
                    'prix' => $menu[$i]->getPrix(),
                    'description' => $menu[$i]->getDescription()
                );
                $res[$i] = $data;
            }

            return $this->json(array('error' => false, 'res' => $res));
        }catch (Exception $e) {
        print($e);
        return $this->json(array('error' => true));
    }

    }
    /**
     * @Route("/mobile/menu/edit/", name="menu_edit_mobile", methods={"POST"})
     */
    public function MobileEditMenu(Request $request, MenuElementRepository $rep): Response
    {
        try {
            $id = $request->get("id");
            $nom = $request->get('nom');
            $description = $request->get('description');
            $cat = $request->get("categorie");
            $prix = $request->get("prix");

            $ele = $rep->find($id);
            $ele->setNom($nom);
            $ele->setCategorie($cat);
            $ele->setDescription($description);
            $ele->setPrix((float)$prix);
            $em = $this->getDoctrine()->getManager();
            $em->flush();


            return $this->json(array('error' => false));
        } catch (Exception $e) {
            print($e);
            return $this->json(array('error' => true));
        }
    }

    /**
     * @Route("/mobile/menu/add/", name="mobile_menu_add", methods={"POST"})
     */
    public function MobileAddMenu(Request $request)
    {

        $nom = $request->get('nom');
        $description = $request->get('description');
        $cat = $request->get("categorie");
        $prix = $request->get("prix");
        if ($nom && $description && $cat && $prix) {
            try {
                $OP = new MenuElement();
                $OP->setNom($nom);
                $OP->setDescription($description);
                $OP->setCategorie($cat);
                $OP->setPrix((float)$prix);
                $OP->setImage("d80207a703c8aac12cef6090d5e9af49.jpeg");
                $em = $this->getDoctrine()->getManager();
                $em->persist($OP);
                $em->flush();
                return $this->json(array('error' => false, 'adsID' => $OP->getId()));
            } catch (Exception $e) {
                return $this->json(array('error' => true));
            }

        } else {
            return $this->json(array('error' => true));
        }
    }


    /**
     * @Route("/mobile/menu/delete/{id}", name="mobile_menu_delete", methods={"POST"})
     */
    public function MobileDeleteMenu( $id, MenuElementRepository $rep)
    {

        try {
            $m = $rep->find($id);
            //  if ($this->isCsrfTokenValid('delete' .$id, $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($m);
            $em->flush();
            //}

            return $this->json(array('error' => false));
        } catch (Exception $e) {
            return $this->json(array('error' => true));
        }
    }


}
