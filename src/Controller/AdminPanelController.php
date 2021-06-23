<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UsersListFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminPanelController extends AbstractController
{
    #[Route('/admin/panel', name: '/admin_panel')]
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $users = $entityManager->getRepository(User::class)->findAll();
        return $this->render('admin_panel/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/admin/remove_user/{user_id}',name:'remove_user')]
    public function remove_user(Request $request): Response
    {
        $user_id = $request->attributes->get('user_id');
        echo($user_id);
        $entityManager = $this->getDoctrine()->getRepository(User::class);
        $user = $entityManager->find($user_id);

        $entityManager->remove($user);
        $entityManager->flush();

        return new Response($user_id);

    }
}
