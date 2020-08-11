<?php

namespace App\Controller;

use App\Entity\Bike;
use App\Form\BikeType;
use App\Repository\BikeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BikeController extends AbstractController
{
    /**
     * @Route("/velo", name="bike")
     */
    public function index(BikeRepository $repo)
    {
        $bikes = $repo->findAll();

        return $this->render('bike/index.html.twig', [
            'controller_name' => 'BikeController',
            'bikes' => $bikes,
        ]);
    } 
    
    /**
     * @Route("/velo/{id}", name="bike_show")
     */
    public function bikeShow(Bike $bike)
    {

        return $this->render('bike/show.html.twig', [
            'controller_name' => 'BikeController',
            'bike' => $bike,
        ]);
    } 
    
    /**
    * @Route("/velo/ajouter", name="bike_add")
    */
   public function bikeAddForm(Request $request)
   {

        $bike = new Bike();

        $form = $this->createForm(BikeType::class, $bike); 

        $em = $this->getDoctrine()->getManager();

        $form-> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($bike);
            $em->flush();
            
        }


       return $this->render('bike/add.html.twig', [
           'controller_name' => 'BikeController',
           'form' => $form->createView(),
       ]);
   }
}
