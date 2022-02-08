<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use App\Form\AccountType;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\Alert\AlertServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

    #[Route('/users/profile/', name: 'user_profile')]
    /**
     *  @IsGranted("ROLE_USER")
     */
    public function profile(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $orders = $doctrine->getRepository(Order::class)->findAll();

        return $this->render('users/user_profile.html.twig', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    #[Route('/users/profile_edit', name: 'profile_edit')]
    /**
     *@IsGranted("ROLE_USER")
     */
    public function EditProfile(Request $request, EntityManagerInterface $manager, AlertServiceInterface $alert)
    {
        // getUser : personne déjà connectée

        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            $alert->success("Vos informations ont bien été modifiées");

            return $this->redirectToRoute('user_profile');
        }
        return $this->render('users/profile_edit.html.twig', [
            'profileForm' => $form->createView(),

        ]);
        return $this->render('users/user_profile.html.twig');
    }

    #[Route('users/show_order/{id}', name: 'see_order')]
    /**
     * @Security("user === order.getUser()", message = "Cette commande ne vous appartient pas")
     */
    public function seeOrder(Order $order): Response
    {
        return $this->render('/users/order_detail.html.twig', [
            'order' => $order
        ]);
    }
}
