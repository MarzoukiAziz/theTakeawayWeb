<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Client;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;



class ArticleController extends AbstractController
{

    /**
     * @Route("/articleclient", name="article_client_index", methods={"GET"})
     */
    public function ClientIndex(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/client/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }
    /**
     * @Route("/article", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/article/new", name="article_new", methods={"GET", "POST"})
     */
    public function new(Request $request,EntityManagerInterface $entityManager ): Response
    {
        $article = new Article();
        $form = $this->createForm ( ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($article->getBanner()=="")
                $article->setBanner('no_image.jpg');
                    else {
                        $file = new File($article->getBanner());
                        $fileName= md5(uniqid()).'.'.$file->guessExtension();
                        $file->move( $this -> getParameter ('upload_directory') , $fileName);
                        $article->setBanner($fileName);
                    }

            //to change later
            $adminId="1";
            $admin = $this->getDoctrine()->getRepository(Client::class)->find($adminId);
            $article->setAuthor($admin);


            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/article/{id}/edit", name="article_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $name= $article->getBanner();
        $form = $this-> createForm ( ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($article->getBanner() == "")
                $article->setBanner($name);
            else {

                $file = new File($article->getBanner());
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('upload_directory'), $fileName);
                $article->setBanner($fileName);
                if ($name != "no_image.jpg")
                    if (file_exists("couverture/" . $name))
                        unlink("couverture/" . $name);

            }
            $entityManager->flush();

            return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/{id}", name="article_delete", methods={"POST"})
     */
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/article/{id}/detail", name="article_client_show", methods={"GET"})
     */
    public function showArticle(Article $article): Response
    {
        return $this->render('article/client/detail.html.twig', [
            'article' => $article,
        ]);
    }
}
