<?php

namespace App\Controller;

use App\Entity\Bike;
use App\Entity\User;
use App\Form\BikeType;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Entity\CommentLike;
use App\Repository\BikeRepository;
use App\Repository\CommentRepository;
use App\Repository\CommentLikeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
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
    public function bikeShow(Request $request, Bike $bike, CommentRepository $repo)
    {
        $user = new User();

        $user = $this->getUser();

        $comment = new Comment();
        if ($user != null) {
            
            $author = $user->getUsername();
            
            $comment = $comment->setAuthor($user);
            $comment = $comment->setName($author);
            $comment = $comment->setBike($bike);
        }
            

        
        $form = $this->createForm(CommentType::class, $comment); 

        $em = $this->getDoctrine()->getManager();

        $form-> handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('bike');
        }

        $comments = $repo->findAll();

        

        return $this->render('bike/show.html.twig', [
            'controller_name' => 'BikeController',
            'bike' => $bike,
            'form' => $form->createView(),
            'bike' => $bike,
            'comments' => $comments
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
   /**
    * like and unlike a comment
    * @Route("/velo/comment/{id}/like", name="comment_like")
    */
   public function like(Comment $comment, CommentLikeRepository $likeRepo) : Response {
    $em = $this->getDoctrine()->getManager();

    $bike = New Bike();
    $bike = $comment->getBike();
    $bikeId = $bike->getId();

    $user = $this->getUser();

    if (!$user) return $this->redirectToRoute('bike_show',[
        'id' => $bikeId,
    ]);

    if ($comment->isLikedByUser($user)) {

        $like = $likeRepo->findOneBy([
            'comment' => $comment,

        ]);

        $em->remove($like);
        $em->flush();
        
        return $this->redirectToRoute('bike_show',[
            'id' => $bikeId,
        ]);


    }

    $like = New CommentLike();
    $like->setComment($comment)
         ->setUser($user);

    $em->persist($like);
    $em->flush();
    
    return $this->redirectToRoute('bike_show',[
        'id' => $bikeId,
    ]);
    
    
    

   }

}
