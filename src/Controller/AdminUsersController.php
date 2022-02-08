<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserEditFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\Alert\AlertServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUsersController extends AbstractController
{
    #[Route('/admin/show_users', name: 'show_users')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function showUsers(ManagerRegistry $doctrine)
    {
        $user = $doctrine->getRepository(User::class)->findAll();

        return $this->render('admin/users/show_users.html.twig', [
            'users' => $user
        ]);
    }

    #[Route('/admin/user/edit_user/{id}', name: 'edit_user')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function editUser(User $user, Request $request, EntityManagerInterface $manager, AlertServiceInterface $alert): Response
    {
        $form = $this->createForm(UserEditFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
            $alert->success("L'utilisateur a bien été modifié");
            return $this->redirectToRoute('show_users');
        }
        return $this->render('admin/users/edit_user.html.twig', [
            'editUserForm' => $form->createView()
        ]);
    }

    #[Route('/admin/user/{id}/delete', name: 'delete_user')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteUser(User $user, EntityManagerInterface $manager, AlertServiceInterface $alert)
    {
        $manager->remove($user);
        $manager->flush();
        $alert->success("L'utilisateur a bien été supprimé");
        return $this->redirectToRoute('show_users');
    }
}
