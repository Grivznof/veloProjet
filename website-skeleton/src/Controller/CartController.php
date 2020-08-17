<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\User;
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




        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'items' => $panierData, 
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

    /**
     * @Route("/panier/acheter", name="cart_buy")
     */
    public function cartBuy(SessionInterface $session, BikeRepository $bikeRepo)
    {
        $user = New User();
        $user = $this->getUser();

        $panier = $session->get('panier', []);

        $panierData = [];

        foreach ($panier as $id => $quantity) {
            $panierData[] = [
                'product' => $bikeRepo->find($id),
                'quantity' => $quantity,
            ];
        }
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('cart/buy.html.twig', [
            'controller_name' => 'CartController',
            'items' => $panierData,
            'user' => $user 
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }
}
