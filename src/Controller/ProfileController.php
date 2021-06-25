<?php

namespace App\Controller;

use App\Entity\OrderCategory;
use App\Entity\Post;
use App\Entity\Prototype;
use App\Entity\Research;
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
    public function profile(Request $request, $author_id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find($author_id);
        if ($user instanceof User) {
            $descriptionForm = $this->createFormBuilder()->add('description')->getForm();
            $descriptionForm->handleRequest($request);
            if ($descriptionForm->isSubmitted() && $descriptionForm->isValid()) {
                $user->setDescription($descriptionForm->get('description')->getData());
                $entityManager->flush();
            }
            $params = [
                'researches' => $entityManager->getRepository(Research::class)->findBy(['author' => $user], [
                    'createdAt' => 'DESC'
                ]),
                'prototypes' => $entityManager->getRepository(Prototype::class)->findBy(['author' => $user], [
                    'createdAt' => 'DESC'
                ]),
                'ideas' => $user->getBusinessIdeas(),
                'projects' => $user->getBusinessProjects(),
                'username' => $user->getUsername(),
                'description' => $user->getDescription(),
                'descriptionForm' => null
            ];
            if ($user == $this->getUser()) {
                $params['descriptionForm'] = $descriptionForm->createView();
            }
            return $this->render('profile.html.twig', $params);
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
