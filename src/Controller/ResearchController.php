<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Prototype;
use App\Entity\Research;
use App\Entity\User;
use App\Form\CommentFormType;
use App\Form\ResearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ResearchController extends AbstractController
{
    #[Route('/researches', name: 'researches')]
    public function researches() {
        $entityManager = $this->getDoctrine()->getManager()->getRepository(Research::class);
        return $this->render('researches.html.twig', [
            'researches' => $entityManager->findBy([], [
                'createdAt' => 'DESC',
            ])
        ]);
    }
    #[Route('/research/{research_id}', name: 'research')]
    public function research(Request $request) {
        $research_id = $request->attributes->get('research_id');
        $entityManager = $this->getDoctrine()->getManager();
        $research = $entityManager->getRepository(Research::class)->find($research_id);
        $commentForm = $this->createFormBuilder()->add('content')->getForm();
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $user = $this->getUser();
            $comment = new Comment();
            $comment->setPostType('research');
            $comment->setPostId($research_id);
            if ($user instanceof User) {
                $comment->setAuthor($user);
            }
            $comment->setContent($commentForm->get('content')->getData());
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirect($request->getUri());
        }
        $comments = $entityManager->getRepository(Comment::class)->findBy([
            'post_type' => 'research', 'post_id' => $research_id
        ], [
            'createdAt' => 'DESC'
        ]);
        return $this->render('research.html.twig', [
            'research' => $research,
            'prototypes' => $research->getPrototypes(),
            'commentForm' => $commentForm->createView(),
            'comments' => $comments
        ]);
    }
    #[Route('/make_research', name: 'make_research')]
    public function makeResearch(Request $request) {
        $research = new Research();
        $researchForm = $this->createForm(ResearchFormType::class, $research);
        $researchForm->handleRequest($request);
        if ($researchForm->isSubmitted() && $researchForm->isValid()) {
            $research->setTitle($researchForm->get('title')->getData());
            $research->setContent($researchForm->get('content')->getData());
            $research->setAuthor($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($research);
            $entityManager->flush();
            return $this->redirectToRoute('researches');
        }
        return $this->render('make_research_form.html.twig', [
            'researchForm' => $researchForm->createView()
        ]);
    }
}