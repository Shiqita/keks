<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Prototype;
use App\Entity\Research;
use App\Entity\User;
use App\Form\CommentFormType;
use App\Form\PostFormType;
use App\Form\PrototypeFormType;
use App\Repository\ResearchRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PrototypeController extends AbstractController
{
    #[Route('/prototypes', name: 'prototypes')]
    public function prototypes() {
        $entityManager = $this->getDoctrine()->getManager()->getRepository(Prototype::class);
        return $this->render('prototypes.html.twig', [
            'prototypes' => $entityManager->findBy([], [
                'createdAt' => 'DESC',
            ])
        ]);
    }
    #[Route('/prototype/{prototype_id}', name: 'prototype')]
    public function prototype(Request $request) {
        $prototype_id = $request->attributes->get('prototype_id');
        $entityManager = $this->getDoctrine()->getManager();
        $prototype = $entityManager->getRepository(Prototype::class)->find($prototype_id);
        $commentForm = $this->createFormBuilder()->add('content')->getForm();
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $user = $this->getUser();
            $comment = new Comment();
            $comment->setPostType('prototype');
            $comment->setPostId($prototype_id);
            if ($user instanceof User) {
                $comment->setAuthor($user);
            }
            $comment->setContent($commentForm->get('content')->getData());
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirect($request->getUri());
        }
        $comments = $entityManager->getRepository(Comment::class)->findBy([
            'post_type' => 'prototype',  'post_id' => $prototype_id
        ], [
            'createdAt' => 'DESC'
        ]);
        return $this->render('prototype.html.twig', [
            'prototype' => $prototype,
            'ideas' => $prototype->getBusinessIdeas(),
            'parent' => $prototype->getResearch(),
            'commentForm' => $commentForm->createView(),
            'comments' => $comments
        ]);
    }
    #[Route('/make_prototype', name: 'make_prototype')]
    public function makePrototype(Request $request) {
        $prototype = new Prototype();
        $prototypeForm = $this->createForm(PrototypeFormType::class, $prototype);
        $prototypeForm->handleRequest($request);
        if ($prototypeForm->isSubmitted() && $prototypeForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $prototype->setTitle($prototypeForm->get('title')->getData());
            $prototype->setContent($prototypeForm->get('content')->getData());
            $prototype->setAuthor($this->getUser());
            $research_id = $request->get('research_id', null);
            $prototype->setResearch($entityManager->getRepository(Research::class)->find($research_id));
            $entityManager->persist($prototype);
            $entityManager->flush();
            return $this->redirectToRoute('prototypes');
        }
        return $this->render('make_prototype_form.html.twig', [
            'prototypeForm' => $prototypeForm->createView()
        ]);
    }
}