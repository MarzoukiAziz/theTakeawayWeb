<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\BlogClient;
use App\Form\BlogClientType;
use App\Repository\BlogClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;


class BlogClientController extends AbstractController
{
    /**
     * @Route("/blog/{id}/detail", name="blog_admin_show", methods={"GET"})
     */
    public function AdminShow(BlogClient $blogClient): Response
    {
        return $this->render('blog_client/admin/detail.html.twig', [
            'blog_client' => $blogClient,
        ]);
    }

    /**
     * @Route("/blog/blogadmin", name="blog_admin_index", methods={"GET"})
     */
    public function AdminIndex(BlogClientRepository $blogClientRepository): Response
{
    return $this->render('blog_client/admin/index.html.twig', [
        'blog_clients' => $blogClientRepository->findAll(),
    ]);
}
    /**
     * @Route("/blog", name="blog_client_index", methods={"GET"})
     */
    public function index(BlogClientRepository $blogClientRepository): Response
    {
        return $this->render('blog_client/index.html.twig', [
            'blog_clients' => $blogClientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/blog/new", name="blog_client_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $blogClient = new BlogClient();
        $form = $this->createForm(BlogClientType::class, $blogClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($blogClient->getImage()=="")
                $blogClient->setImage('no_image.jpg');
            else {
                $file = new File($blogClient->getImage());
                $fileName= md5(uniqid()).'.'.$file->guessExtension();
                $file->move( $this->getParameter ('upload_directory') , $fileName);
                $blogClient->setImage($fileName);
            }
            $date = new \DateTime();
            $blogClient->setDate($date);
            $blogClient->setAuthor($this->getUser());
            $blogClient->setStatut("En Attente");
            $entityManager->persist($blogClient);
            $entityManager->flush();

            return $this->redirectToRoute('blog_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_client/new.html.twig', [
            'blog_client' => $blogClient,
            'f' => $form->createView(),
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_client_show", methods={"GET"})
     */
    public function show(BlogClient $blogClient): Response
    {
        return $this->render('blog_client/show.html.twig', [
            'blog_client' => $blogClient,
        ]);
    }

    /**
     * @Route("/blog/{id}/edit", name="blog_client_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, BlogClient $blogClient, EntityManagerInterface $entityManager): Response
    {
        $name=$blogClient->getImage();
        $form = $this->createForm(BlogClientType::class, $blogClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                if($blogClient->getImage()=="")
                    $blogClient->setImage($name);
                        else
                        {

                $file = new File($blogClient->getImage());
                $fileName= md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'),$fileName);
                $blogClient->setImage($fileName);
                if($name != "no_image.jpg")
                    if( file_exists ("couverture/".$name))
                        unlink("couverture/".$name);

            }
            $entityManager->flush();

            return $this->redirectToRoute('blog_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_client/edit.html.twig', [
            'blog_client' => $blogClient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_client_delete", methods={"POST"})
     */
    public function delete(Request $request, BlogClient $blogClient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blogClient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($blogClient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('blog_client_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/blog/delete/{id}", name="blog_admin_delete", methods={"POST"})
     */
    public function AdminDelete(Request $request, BlogClient $blogClient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blogClient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($blogClient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('blog_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
