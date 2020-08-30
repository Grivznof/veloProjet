<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/enregistration", name="connexion")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder )
    {
        $user = new User();
        $role = ["ROLE_USER"];

        $form = $this->createForm(RegistrationType::class, $user); 
        
        $em = $this->getDoctrine()->getManager();

        $form-> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user -> setPassword($hash);
            $user->setRoles($role);

            $em->persist($user);
            $em->flush();
            
            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login () {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(){

    }
}
