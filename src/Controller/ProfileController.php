<?php

namespace App\Controller;

use App\Entity\OrderCategory;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ProfileController extends AbstractController
{
    #[Route('/profile/{author_id}', name: 'profile')]
    public function profile($author_id)
    {
        $entityManager = $this->getDoctrine()->getRepository(User::class);
        $user = $entityManager->find($author_id);
        if ($user instanceof User) {
            return $this->render('profile.html.twig', [
                'posts' => $user->getPosts(),
                'username' => $user->getUsername()
            ]);
        }
        return new Response('You are not user!', Response::HTTP_FORBIDDEN);
    }
    #[Route('/user_orders/{user_id}', name: 'user_orders')]
    public function userOrders($user_id) {
        $entityManager = $this->getDoctrine()->getRepository(User::class);
        $user = $entityManager->find($user_id);
        if ($user instanceof User) {
            return $this->render('profile_orders.html.twig', [
                'orders' => $user->getOrders(),
                'username' => $user->getUsername()
            ]);
        }
        return new Response('You are not user!', Response::HTTP_FORBIDDEN);
    }
}
