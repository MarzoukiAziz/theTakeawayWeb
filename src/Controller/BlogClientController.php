<?php

namespace App\Controller;


use App\Entity\Client;
use App\Entity\Commentaire;
use App\Entity\BlogClient;
use App\Entity\Admin;
use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Entity\SearchData;
use App\Form\CommentaireType;
use App\Form\BlogClientType;
use App\Form\ReponseType;
use App\Form\SearchType;
use App\Repository\BlogClientRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use App\Repository\CommentaireRepository;
use Snipe\BanBuilder\CensorWords;
use Twilio\Rest\Client as Twilio ;

class BlogClientController extends AbstractController
{
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
     * @Route("/blog/blogadmin", name="blog_admin_index", methods={"GET", "POST"})
     */
    public function AdminIndex(BlogClientRepository $blogClientRepository ,Request $request): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchType::class,$data);
        $form->handleRequest($request);
        $blog = $blogClientRepository->findSearch($data);

        return $this->render('blog_client/admin/index.html.twig', [
            'blog_clients' => $blog,
            'form'=>$form->createView(),

        ]);
    }

    /**
     * @Route("/blog/{id}/detail/reopen", name="admin-reopen-blog" )
     */
    //change the status of a reservation
    public function reopenBlog($id): Response
    {
        $rep = $this->getDoctrine()->getRepository(blogClient::class);
        $blogClient = $rep->find($id);
        //check if the parameters are correct
        if ($blogClient == null) {
            return $this->redirectToRoute("erreur-back");
        }
        $user = $this->getUser();//badel user el connecter

            $sid = "AC129fc18c3e71f7ed7330e630d246af42"; // Your Account SID from www.twilio.com/console
            $token = "e88bb294159ad8317d59afc2943f0238"; // Your Auth Token from www.twilio.com/console

            $client = new Twilio($sid, $token);
            $message = $client->messages->create(
                '+216'.$user->getNumTel(), // Text this number
                [
                    'from' => '+14793232793', // From a valid Twilio number
                    'body' => 'Félicitations! hello '
                ]
            );



        $blogClient->setStatut("Ouvert");
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('blog_admin_show', ['id' => $blogClient->getId()]);
    }
    /**
     * @Route("/blog/{id}/detail/close", name="admin-close-blog")
     */
    public function deleteBlogByAdmin(Request $request, $id, BlogClientRepository $blogClientRepository): Response
    {
        $rep = $this->getDoctrine()->getRepository(blogClient::class);
        $blogClient = $rep->find($id);
        $rec = $blogClientRepository->find($id);
        $rec->setStatut("Ferme");
        $em = $this->getDoctrine()->getManager();
        $em->persist($rec);
        $em->flush();
        return $this->redirectToRoute('blog_admin_show', ['id' => $blogClient->getId()]);
    }
    /**
     * @Route("/blog/{id}/detail", name="blog_admin_show", methods={"GET" ,"POST"})
     */
    public function AdminShow(BlogClient $blogClient,Request $request): Response
    {


        $commentaire= new commentaire();
        $form = $this->createForm(commentaireType::class, $commentaire);
        $form->handleRequest($request);
        $commentaires = $this->getDoctrine()->getRepository(commentaire::class)
            ->createQueryBuilder('b')
            ->andWhere('b.author=?1')
            ->setParameter(1, $blogClient)
            ->getQuery()
            ->getResult();

        if ($form->isSubmitted() && $form->isValid()) {
            $contenuCommentaire = $form->getData()->getContenu();
            $em = $this->getDoctrine()->getManager();
            $em->getRepository(commentaire::class);
            ///Warning
            /// change this later

            $author = $this->getDoctrine()->getRepository(commentaire::class)->find('2');
            $commentaire->setAuthor($author);
            $date = new \DateTime();
            $commentaire->setDate($date);
            $commentaire->setHeure($date);
            $commentaire->setBlogClient($blogClient);
            $em->persist($commentaire);
            $em->flush();

            return $this->redirectToRoute('blog_admin_show', ['id' => $commentaire->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('blog_client/admin/detail.html.twig', [
            'blog_client' => $blogClient,
            'commentaires' => $commentaires,
            'f' => $form->createView(),



        ]);
    }
    /**
     * @Route("/blog/{id}", name="blog_client_show", methods={"GET","POST"})
     */
    public function show(BlogClient $blogClient, Request $request , PaginatorInterface $paginator): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        $commentaires= $this->getDoctrine()->getRepository(commentaire::class)
            ->createQueryBuilder('b')
            ->andWhere('b.blogClient=?1')
            ->setParameter(1, $blogClient)
            ->getQuery()
            ->getResult();

        if ($form->isSubmitted() && $form->isValid()) {
            $contenuCommentaire = $form->getData()->getContenu();
            $censor = new CensorWords;
            $badwords = $censor->setDictionary('fr');
            $string = $censor->censorString($contenuCommentaire);
            $commentaire->setContenu($string['clean']);
            $em = $this->getDoctrine()->getManager();
            $em->getRepository(Reponse::class);
            $client=$this->getUser();
            $commentaire->setAuthor($client);
            $date = new \DateTime();
            $commentaire->setDate(new \DateTime());
            $commentaire->setBlogClient($blogClient);

            $em->persist($commentaire);
            $em->flush();



            return $this->redirectToRoute('blog_client_show', ['id' => $blogClient->getId()], Response::HTTP_SEE_OTHER);

        }
        $commentaires= $paginator->paginate(
            $commentaires,
            $request->query->getInt('page',1),
            4
        );
        return $this->render('blog_client/show.html.twig', [
            'blog_client' => $blogClient,
            'commentaires' => $commentaires,
            'f' => $form->createView(),


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
