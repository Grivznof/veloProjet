<?php

namespace App\Controller;

use App\Entity\Rent;
use App\Entity\User;
use App\Form\RentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RentController extends AbstractController
{
    /**
     * @Route("/rent", name="rent")
     */
    public function index(Request $request)
    {

        $user = new User();

        $user = $this->getUser();

        $rent = new Rent();

        $rent = $rent->setUser($user);

        $form = $this->createForm(RentType::class, $rent); 

        $em = $this->getDoctrine()->getManager();

        $form-> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($rent);
            $em->flush();
            
            return $this->redirectToRoute('bike');
        }


        return $this->render('rent/index.html.twig', [
            'controller_name' => 'RentController',
            'form' => $form->createView(),
        ]);
    }
}
