<?php

namespace App\Controller;

use App\Entity\Food;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FoodController extends AbstractController
{
    #[Route('/food/{id}/view', name: 'see_food_details')]
    public function index(Food $food): Response
    {
        return $this->render('food/see_food.html.twig', [
            'food' => $food,
        ]);
    }
}
