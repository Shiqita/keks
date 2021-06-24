<?php


namespace App\Controller;


use App\Entity\BusinessIdea;
use App\Entity\Prototype;
use App\Entity\Research;
use App\Form\BusinessIdeaFormType;
use App\Form\PostFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BusinessIdeaController extends AbstractController
{
    #[Route('/ideas', name: 'ideas')]
    public function ideas() {
        $entityManager = $this->getDoctrine()->getManager()->getRepository(BusinessIdea::class);
        return $this->render('ideas.html.twig', [
            'ideas' => $entityManager->findBy([], [
                'createdAt' => 'DESC',
            ])
        ]);
    }
    #[Route('/idea/{idea_id}', name: 'idea')]
    public function idea(Request $request) {
        $idea_id = $request->attributes->get('idea_id');
        $entityManager = $this->getDoctrine()->getManager();
        $idea = $entityManager->getRepository(BusinessIdea::class)->find($idea_id);
        return $this->render('idea.html.twig', [
            'idea' => $idea,
            'projects' => $idea->getBusinessProjects(),
            'parent' => $idea->getPrototype()
        ]);
    }
    #[Route('/make_idea', name: 'make_idea')]
    public function makeIdea(Request $request) {
        $idea = new BusinessIdea();
        $ideaForm = $this->createForm(BusinessIdeaFormType::class, $idea);
        $ideaForm->handleRequest($request);
        if ($ideaForm->isSubmitted() && $ideaForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $idea->setTitle($ideaForm->get('title')->getData());
            $idea->setContent($ideaForm->get('content')->getData());
            $idea->setAuthor($this->getUser());
            $prototype_id = $request->get('prototype_id', null);
            $idea->setPrototype($entityManager->getRepository(Prototype::class)->find($prototype_id));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($idea);
            $entityManager->flush();
            return $this->redirectToRoute('ideas');
        }
        return $this->render('make_idea_form.html.twig', [
            'ideaForm' => $ideaForm->createView()
        ]);
    }
}