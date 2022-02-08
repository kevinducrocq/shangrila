<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/order', name: 'order')]
    /**
     *@IsGranted("ROLE_USER")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $categories = $doctrine->getRepository(Category::class)->findAll();

        return $this->render('order/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/displayfood/{id}/category', name: 'display_food', options: ["expose" => true])]
    public function displayFood(Category $category)
    {
        $foods = $category->getFood();
        return new JsonResponse([
            'response' => $this->render('/order/_listfood.html.twig', [
                'foods' => $foods,
                'category' => $category,
            ])->getContent()
        ]);
    }


}
