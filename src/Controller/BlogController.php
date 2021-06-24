<?php


namespace App\Controller;


use App\Entity\BusinessIdea;
use App\Entity\BusinessProject;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Order;
use App\Entity\OrderCategory;
use App\Entity\Post;
use App\Entity\Prototype;
use App\Entity\Research;
use App\Form\CommentFormType;
use App\Form\OrderFormType;
use App\Form\PostFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index() {
        $entityManager = $this->getDoctrine()->getManager();
        $researches = $entityManager->getRepository(Research::class)->findAll();
        $prototypes = $entityManager->getRepository(Prototype::class)->findAll();
        $ideas = $entityManager->getRepository(BusinessIdea::class)->findAll();
        $projects = $entityManager->getRepository(BusinessProject::class)->findAll();
        foreach ($researches as $post) {
            $post->type = 'research';
            $post->type_id = $post->type . '_id';
        }
        foreach ($prototypes as $post) {
            $post->type = 'prototype';
            $post->type_id = $post->type . '_id';
        }
        foreach ($ideas as $post) {
            $post->type = 'idea';
            $post->type_id = $post->type . '_id';
        }
        foreach ($projects as $post) {
            $post->type = 'project';
            $post->type_id = $post->type . '_id';
        }
        $result = array_merge($researches, $prototypes, $ideas, $projects);
        usort($result, function($a, $b) {
            return $a->getCreatedAt() < $b->getCreatedAt();
        });
        return $this->render('home/index.html.twig', [
            'posts' => $result
        ]);
    }
    #[Route('/post/{post_id}', name: 'full_post')]
    public function fullPost(Request $request) {
        $post_id = $request->attributes->get('post_id');
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Post::class)->find($post_id);
        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setContent($commentForm->get('content')->getData());
            $comment->setPost($post);
            $comment->setAuthor($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('full_post', ['post_id' => $post_id]);
        }
        return $this->render('full_post.html.twig', [
            'post' => $post,
            'comments' => $post->getComments([
                'createdAt' => 'ASC'
            ]),
            'commentForm' => $commentForm->createView()
        ]);
    }
    #[Route('/category/{category_id}', name: 'category')]
    public function categoryPosts($category_id) {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->find($category_id);
        return $this->render('category.html.twig', [
            'posts' => $category->getResearch([
                'createdAt' => 'DESC'
            ]),
            'categoryName' => $category->getName()
        ]);
    }
    #[Route('/orders_category/{category_id}', name: 'orders_category')]
    public function categoryOrders($category_id) {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(OrderCategory::class)->find($category_id);
        return $this->render('orders_category.html.twig', [
            'orders' => $category->getOrders([
                'createdAt' => 'DESC'
            ]),
            'categoryName' => $category->getName()
        ]);
    }
    #[Route('/create_post', name: 'create_post')]
    public function createPost(Request $request) {
        $post = new Post();
        $postForm = $this->createForm(PostFormType::class, $post);
        $postForm->handleRequest($request);
        if ($postForm->isSubmitted() && $postForm->isValid()) {
            $post->setTitle($postForm->get('title')->getData());
            $post->setContent($postForm->get('content')->getData());
            $post->setCategory($postForm->get('category')->getData());
            $post->setAuthor($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('create_post.html.twig', [
            'postForm' => $postForm->createView()
        ]);
    }
    #[Route('/orders', name: 'view_orders')]
    public function orders() {
        $entityManager = $this->getDoctrine()->getManager();
        return $this->render('view_orders.html.twig', [
            'orders' => $entityManager->getRepository(Order::class)->findAll()
        ]);
    }
    #[Route('/create_order', name: 'create_order')]
    public function createOrder(Request $request) {
        $order = new Order();
        $orderForm = $this->createForm(OrderFormType::class, $order);
        $orderForm->handleRequest($request);
        if ($orderForm->isSubmitted() && $orderForm->isValid()) {
            $order->setTitle($orderForm->get('title')->getData());
            $order->setContent($orderForm->get('content')->getData());
            $order->setCategory($orderForm->get('category')->getData());
            $order->setContact($orderForm->get('contact')->getData());
            $order->setAuthor($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();
            return $this->redirectToRoute('view_orders');
        }
        return $this->render('create_order.html.twig', [
            'orderForm' => $orderForm->createView()
        ]);
    }
    #[Route('/{post_id}/create_comment', name: 'create_comment')]
    public function createComment(Request $request) {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setContent($commentForm->get('content')->getData());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('full_post.html.twig', [
            'commentForm' => $commentForm->createView()
        ]);
    }
}