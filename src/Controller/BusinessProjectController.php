<?php


namespace App\Controller;


use App\Entity\BusinessIdea;
use App\Entity\BusinessProject;
use App\Entity\Prototype;
use App\Entity\Research;
use App\Form\BusinessProjectFormType;
use App\Form\PostFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BusinessProjectController extends AbstractController
{
    #[Route('/projects', name: 'projects')]
    public function projects() {
        $entityManager = $this->getDoctrine()->getManager()->getRepository(BusinessProject::class);
        return $this->render('projects.html.twig', [
            'projects' => $entityManager->findBy([], [
                'createdAt' => 'DESC',
            ])
        ]);
    }
    #[Route('/project/{project_id}', name: 'project')]
    public function project(Request $request) {
        $project_id = $request->attributes->get('project_id');
        $entityManager = $this->getDoctrine()->getManager();
        $project = $entityManager->getRepository(BusinessProject::class)->find($project_id);
        return $this->render('project.html.twig', [
            'project' => $project,
            'parent' => $project->getBusinessIdea()
        ]);
    }
    #[Route('/make_project', name: 'make_project')]
    public function makeProject(Request $request) {
        $project = new Businessproject();
        $projectForm = $this->createForm(BusinessProjectFormType::class, $project);
        $projectForm->handleRequest($request);
        if ($projectForm->isSubmitted() && $projectForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $project->setTitle($projectForm->get('title')->getData());
            $project->setContent($projectForm->get('content')->getData());
            $project->setAuthor($this->getUser());
            $idea_id = $request->get('idea_id', null);
            $project->setBusinessIdea($entityManager->getRepository(BusinessIdea::class)->find($idea_id));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();
            return $this->redirectToRoute('projects');
        }
        return $this->render('make_project_form.html.twig', [
            'projectForm' => $projectForm->createView()
        ]);
    }
}