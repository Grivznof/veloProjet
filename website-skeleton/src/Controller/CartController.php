<?php

namespace App\Controller;

use App\Repository\BikeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart")
     */
    public function index(SessionInterface $session, BikeRepository $bikeRepo)
    {

        $panier = $session->get('panier', []);

        $panierData = [];

        foreach ($panier as $id => $quantity) {
            $panierData[] = [
                'product' => $bikeRepo->find($id),
                'quantity' => $quantity,
            ];
        }

        $total = 0;



        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'items' => $panierData,
            'total' => $total,
        ]);
    }
    /**
     * @Route("/panier/ajouter/{id}", name="cart_add")
     */
    public function add($id, SessionInterface $session){
        
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }


        $session->set('panier', $panier);

        return $this->redirectToRoute("cart");
    }

    /**
     * @Route("/panier/supprimer/{id}", name="cart_remove")
     */
    public function remove($id, SessionInterface $session){
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $session -> set('panier', $panier);

        return $this->redirectToRoute("cart");
    }
}
