<?php

namespace App\Controller;

use App\Entity\Food;
use App\Entity\User;
use App\Entity\Order;
use App\Entity\Comment;
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
    public function index(ManagerRegistry $manager): Response
    {
        $users = $manager->getRepository(User::class);
        $userNumber = $users->createQueryBuilder('a')->select('count(a.id)')->getQuery()->getSingleScalarResult();

        $orders = $manager->getRepository(Order::class);
        $orderNumber = $orders->createQueryBuilder('a')->select('count(a.id)')->getQuery()->getSingleScalarResult();

        $foods = $manager->getRepository(Food::class);
        $foodNumber = $foods->createQueryBuilder('a')->select('count(a.id)')->getQuery()->getSingleScalarResult();

        $comments = $manager->getRepository(Comment::class);
        $commentNumber = $comments->createQueryBuilder('a')->select('count(a.id)')->getQuery()->getSingleScalarResult();

        return $this->render('admin/index.html.twig', [
            'userNumber' => $userNumber,
            'orderNumber' => $orderNumber,
            'foodNumber' => $foodNumber,
            'commentNumber' => $commentNumber,
        ]);
    }
}
