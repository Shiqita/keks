<?php


namespace App\Controller;


use App\Entity\Prototype;
use App\Entity\Research;
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
        return $this->render('research.html.twig', [
            'research' => $research,
            'prototypes' => $research->getPrototypes()
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