<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Form\RestaurantType;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;



     class RestaurantController extends AbstractController
{


    /**

     * @Route ("/AfficheClient",name="AfficheClient" , methods={"GET"} )
     */

    public function AfficheClient(RestaurantRepository $repository)
    {
        // $repo=$this->getDoctrine()->getRepository(Restaurant::Res)
        $restaurant = $repository->findAll();
        return $this->render('restaurant/client/index.html.twig',
            ['restaurant' => $restaurant,]);

    }

         /**

          * @Route ("/Affiche",name="AfficheR" , methods={"GET"} )

          */


         public function Affiche(RestaurantRepository $repository){
             // $repo=$this->getDoctrine()->getRepository(Restaurant::Res)
             $restaurant=$repository->findAll();
             return $this->render('restaurant/Affiche.html.twig',
                 ['restaurant'=>$restaurant]);

         }

         /**
          * @Route("/{id}", name="restaurant_showC", methods={"GET"})
          */
         public function showclient(Restaurant $restaurant): Response
         {
             return $this->render('restaurant/client/AfficheRC.html.twig', [
                 'restaurant' => $restaurant,
             ]);
         }








         /**
     * @Route("/{id}", name="restaurant_show" , methods={"GET"})
     */
    public function show(Restaurant $restaurant): Response
    {
        return $this->render('restaurant/show.html.twig', [
            'restaurant' => $restaurant,
        ]);
    }

    /**
     * @Route ("/supp/{id}",name="del")
     */
    function Delete($id,RestaurantRepository $repository){
        $restaurant=$repository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($restaurant);
        $em->flush();
        return $this->redirectToRoute("AfficheR");
    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("restaurant/Add",name="restaurant_add")
     */

    function Add(Request $request){
        $restaurant=new restaurant();
      $form=$this->createForm(RestaurantType::class,$restaurant);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()){
           if($restaurant->getImages()=="")
               $restaurant->setImages("no_images.jpg");
           else
           {
               $images=array();
               foreach ( $restaurant->getImages() as $x){
                   $file=new File($x);
                   $fileName=md5(uniqid()).'.'.$file->guessExtension();
                   $file->move($this->getParameter('upload_directory'),$fileName);

                   array_push($images,$fileName);


               }

               $restaurant->setImages($images);
           }
          $em=$this->getDoctrine()->getManager();
          $em->persist($restaurant);
          $em->flush();
          return $this->redirectToRoute('AfficheR');
      }
      return $this->render('restaurant/Add.html.twig',[
          'form'=>$form->createView()
      ]);
    }




    /**
     * @Route("restaurant/Update/{id}",name="update")
     */

    function Update(RestaurantRepository $repository,$id,Request $request){
    $restaurant=$repository->find($id);
    $name=$restaurant->getImages();
    $form=$this->createForm(RestaurantType::class,$restaurant);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()){
        if($restaurant->getImages()=="")
            $restaurant->setImages("no_images.jpg");
        else
        {
            $images=array();
            foreach ( $restaurant->getImages() as $x){
                $file=new File($x);
                $fileName=md5(uniqid()).'.'.$file->guessExtension();
                $file->move($this->getParameter('upload_directory'),$fileName);

                array_push($images,$fileName);


            }

            $restaurant->setImages($images);
        }
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('AfficheR');
    }
    return $this->render('restaurant/Update.html.twig',[
        'f'=>$form->createView()
    ]);

    }






}
