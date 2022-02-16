<?php

namespace App\Controller;

use App\Entity\Food;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\FoodRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(UserRepository $userRepository, OrderRepository $orderRepository, FoodRepository $foodRepository, CommentRepository $commentRepository): Response
    {
        $userNumber = $userRepository->createQueryBuilder('a')->select('count(a.id)')->getQuery()->getSingleScalarResult();
        $orderNumber = $orderRepository->createQueryBuilder('a')->select('count(a.id)')->getQuery()->getSingleScalarResult();
        $foodNumber = $foodRepository->createQueryBuilder('a')->select('count(a.id)')->getQuery()->getSingleScalarResult();
        $commentNumber = $commentRepository->createQueryBuilder('a')->select('count(a.id)')->getQuery()->getSingleScalarResult();

        return $this->render('admin/index.html.twig', [
            'userNumber' => $userNumber,
            'orderNumber' => $orderNumber,
            'foodNumber' => $foodNumber,
            'commentNumber' => $commentNumber,
        ]);
    }
}
