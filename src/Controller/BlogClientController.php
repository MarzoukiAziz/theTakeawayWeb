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

    /////////////////////////front-office///////////////////////
    /**
     * @Route("/blog", name="blog_client_index", methods={"GET"})
     */
    public function blogs(BlogClientRepository $blogClientRepository): Response
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
            $this->addFlash("success","wait for your blog acceptation ,you'll receive a message soon");
            return $this->redirectToRoute('blog_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_client/new.html.twig', [
            'blog_client' => $blogClient,
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
            $censor = new CensorWords();
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
                    if( file_exists ("couvertures/".$name))
                        unlink("couvertures/".$name);

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



    ////////////////////////back-office////////////////////////
    /**
     * @Route("/admin/blog/", name="blog_admin_index", methods={"GET", "POST"})
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
     * @Route("/admin/blog/{id}/detail/reopen", name="admin-reopen-blog" )
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
                    'body' => 'Félicitations! votre blog a été accepté! Visiter notre site pour le voir. '
                ]
            );



        $blogClient->setStatut("Ouvert");
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('blog_admin_show', ['id' => $blogClient->getId()]);
    }
    /**
     * @Route("/admin/blog/{id}/detail/close", name="admin-close-blog")
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
     * @Route("/admin/blog/{id}/detail", name="blog_admin_show", methods={"GET" ,"POST"})
     */
    public function AdminShow(BlogClient $blogClient,Request $request): Response
    {


        $commentaire= new commentaire();
        $form = $this->createForm(commentaireType::class, $commentaire);
        $form->handleRequest($request);
        $commentaires = $this->getDoctrine()->getRepository(commentaire::class)
            ->createQueryBuilder('b')
            ->andWhere('b.blogClient=?1')
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
     * @Route("/admin/blog/delete/{id}", name="blog_admin_delete", methods={"POST"})
     */
    public function AdminDelete(Request $request, BlogClient $blogClient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blogClient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($blogClient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('blog_admin_index', [], Response::HTTP_SEE_OTHER);
    }





    /////////////////////////Mobile Services//////////////////////
    /**
     * @Route("/mobile/blogs/", name="mobile_blogs", methods={"GET"})
     */
    public function mobileBlogs(BlogClientRepository $rep,request $request)
    {
        $res = $rep->findAll();

        $response = array();

        for ($i = 0; $i < sizeof($res); $i++) {
            $data = array(
                'id' => $res[$i]->getId(),
                'title' => $res[$i]->getTitle(),
                'date'=>$res[$i]->getDate(),
                'contenu' => $res[$i]->getContenu(),
                'aid' => $res[$i]->getAuthor()->getId(),
                'aname'=> $res[$i]->getAuthor()->getNom()." ".$res[$i]->getAuthor()->getPrenom(),
                'statut' => $res[$i]->getStatut(),
                'image'=>$res[$i]->getImage()

            );
            $response[$i] = $data;
        }

        return $this->json(array('error' => false, 'res' => $response));


    }


    /**
     * @Route("/mobile/blog/add/", name="mobile_blog_add", methods={"POST"})
     */
    public function MobileAddBlog(Request $request)
    {

        $title = $request->get('title');
        $contenu = $request->get('contenu');
        $aid = $request->get("author");
        if ($title && $contenu && $aid) {
            try {
                $OP = new BlogClient();
                $date = new \DateTime();
                $OP->setDate($date);
                $OP->setAuthor($this->getDoctrine()->getRepository(Client::class)->find($aid));
                $OP->setStatut("En Attente");
                $OP->setImage('no_image.jpg');
                $OP->setTitle($title);
                $OP->setContenu($contenu);
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
     * @Route("/mobile/blog/delete/{id}", name="mobile_blog_delete", methods={"POST"})
     */
    public function MobileDeleteBlog($id, Request $request,EntityManagerInterface $entityManager)
    {
        try {
            $blogClient = $this->getDoctrine()->getRepository(BlogClient::class)->find($id);

            foreach ($blogClient->getCommentaires() as $c){
                $entityManager->remove($c);
            }
            $entityManager->remove($blogClient);
            $entityManager->flush();

            return $this->json(array('error' => false));
        } catch (Exception $e) {
            return $this->json(array('error' => true));
        }
    }

    /**
     * @Route("/mobile/comments/{bid}", name="mobile_comments", methods={"GET"})
     */
    public function mobileComments(Request $request,$bid,BlogClientRepository $rep)
    {
        $c = $rep->find($bid);
        $res=$c->getCommentaires();
       $response = array();

        for ($i = 0; $i < sizeof($res); $i++) {
                $data = array(
                    'id' => $res[$i]->getId(),
                    'date' => $res[$i]->getDate(),
                    'contenu' => $res[$i]->getContenu(),
                    'aid' => $res[$i]->getAuthor()->getId(),
                    'aname' => $res[$i]->getAuthor()->getNom() . " " . $res[$i]->getAuthor()->getPrenom(),
                );
                array_push($response,$data);
        }

        return $this->json(array('error' => false, 'res' => $response));


    }

    /**
     * @Route("/mobile/comment/add/", name="mobile_comment_add", methods={"POST"})
     */
    public function MobileAddComment(Request $request)
    {

        $blogid = $request->get('blogid');
        $contenu = $request->get('contenu');
        $aid = $request->get("author");
        if ($blogid && $contenu && $aid) {
            try {
                $OP = new Commentaire();
                $date = new \DateTime();
                $OP->setDate($date);
                $OP->setAuthor($this->getDoctrine()->getRepository(Client::class)->find($aid));
                $OP->setBlogClient($this->getDoctrine()->getRepository(BlogClient::class)->find($blogid));
                $OP->setContenu($contenu);
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
     * @Route("/mobile/blog/change/", name="menu_blog_change_statut", methods={"POST"})
     */
    public function MobileChangeStatutBlog(Request $request): Response
    {
        try {
            $id = $request->get("id");
            $statut = $request->get('statut');
            $rep = $this->getDoctrine()->getRepository(BlogClient::class);
            $rev = $rep->find($id);
            $rev->setStatut($statut);
            $em = $this->getDoctrine()->getManager();
            $em->flush();


            return $this->json(array('error' => false));
        } catch (Exception $e) {
            print($e);
            return $this->json(array('error' => true));
        }
    }


}
