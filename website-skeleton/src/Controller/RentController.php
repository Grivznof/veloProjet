<?php

namespace App\Controller;


use App\Entity\Rent;
use App\Entity\User;
use App\Form\RentType;
use App\Repository\RentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RentController extends AbstractController
{
    /**
     * @Route("/location", name="rent")
     */
    public function index(Request $request)
    {

        $user = new User();

        $panierData = [];


        $user = $this->getUser();
        $email = $user->getEmail();

        $rent = new Rent();

        $rent = $rent->setUser($user);

        $form = $this->createForm(RentType::class, $rent); 

        $em = $this->getDoctrine()->getManager();

        $form-> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($rent);
            $em->flush();

            return $this->render('rent/confirm.html.twig');
        }


        return $this->render('rent/index.html.twig', [
            'controller_name' => 'RentController',
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/location/liste", name="rent_show")
     */
    public function rentShow(Request $request,RentRepository $repo )
    {
        $rent = $repo->findAll();

        return $this->render('rent/rent.html.twig', [
            'controller_name' => 'RentController',
            'rents' => $rent,
        ]);
    }
}
