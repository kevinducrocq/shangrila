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

    private $manager;
    private $alert;
    public function __construct(EntityManagerInterface $manager, AlertServiceInterface $alert)
    {
        $this->alert = $alert;
        $this->manager = $manager;
    }

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
    public function editUser(User $user, Request $request): Response
    {
        $form = $this->createForm(UserEditFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($user);
            $this->manager->flush();
            $this->alert->success("L'utilisateur a bien été modifié");
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
    public function deleteUser(User $user)
    {
        $this->manager->remove($user);
        $this->manager->flush();
        $this->alert->success("L'utilisateur a bien été supprimé");
        return $this->redirectToRoute('show_users');
    }
}
