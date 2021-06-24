<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Product;
use App\Form\CommentFormType;
use App\Form\SearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'products')]
    public function index(Request $request) {
        $entityManager = $this->getDoctrine()->getRepository(Product::class);
        $searchForm = $this->createFormBuilder()
            ->add("name", null, ['required' => false])
            ->add("type", null, ['required' => false])
            ->add("city", null, ['required' => false])
            ->getForm();
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            $products = $entityManager->findProducts($searchForm->get('name'), $searchForm->get('type'), $searchForm->get('city'));
            return $this->render('view_products.html.twig', [
                'searchForm' => $searchForm->createView(),
                'products' => $products
            ]);
        }
        return $this->render('view_products.html.twig', [
            'searchForm' => $searchForm->createView(),
            'products' => $entityManager->findAll()
        ]);
    }
}