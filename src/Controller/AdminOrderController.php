<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminOrderController extends AbstractController
{
    #[Route('/admin/order', name: 'show_orders')]
    public function showOrders(ManagerRegistry $doctrine): Response
    {
        $order = $doctrine->getRepository(Order::class)->findAll();

        return $this->render('admin/order/show_orders.html.twig', [
            'orders' => $order,
        ]);
    }
}
