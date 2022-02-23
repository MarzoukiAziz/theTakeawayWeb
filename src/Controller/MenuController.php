<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\MenuElement;
use App\Entity\Client;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
class MenuController extends AbstractController
{
    /**
     * @Route("/menu", name="menu")
     */
    public function index(): Response
    {
        return $this->render('menu/afficeh2-menu.html.twig', [
            'form' =>$this->getDoctrine()->getRepository(MenuElement::class)->findAll()

        ]);
    }

    /**
     * @Route("/listmenu", name="listmenu")
     */
    public function listmenu()
    {
        $fr = $this->getDoctrine()->getRepository(MenuElement::class)->findAll();
        return $this->render('menu/affiche-menu.html.twig', array("form" => $fr));
    }

    /**
 * @Route("/{id}/favorite", name="article_favorite", methods={"GET","POST"})
 * @param Request $request
 * @param MenuElement $menu

 * @return Response
 */
    public function Wishlist(Request $request, MenuElement $menu): Response
    {

            $this->getUser()->addWishlist($menu);

        $em = $this->getDoctrine()->getManager();

        $em->flush();

        return $this->json([
            'isWishlist' => $this->getUser()->isWishlist($menu)
        ]);
    }

}
