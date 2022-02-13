<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\Alert\AlertServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{

    private $manager;
    private $alert;
    public function __construct(EntityManagerInterface $manager, AlertServiceInterface $alert)
    {
        $this->alert = $alert;
        $this->manager = $manager;
    }

    #[Route('/admin/comment', name: 'show_comments')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function showComments(CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findBy(array(), array('status' => 'ASC'));

        return $this->render('admin/comments/show_comments.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/admin/validate_comment/{id}', name: 'validate_comment')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function validateComment(Comment $comment)
    {
        $comment->setStatus(1);
        $this->manager->persist($comment);
        $this->manager->flush();
        $this->alert->success("Le commentaire a été publié sur la page d'accueil");
        return $this->redirectToRoute('show_comments');
    }

    #[Route('/admin/hide_comment/{id}', name: 'hide_comment')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function hideComment(Comment $comment)
    {
        $comment->setStatus(0);
        $this->manager->persist($comment);
        $this->manager->flush();
        $this->alert->warning('Le commentaire a été retiré de la page d\'accueil');
        return $this->redirectToRoute('show_comments');
    }

    #[Route('/admin/comment/edit_comment/{id}', name: 'edit_comment')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function editComment(Comment $comment, Request $request): Response
    {

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($comment);
            $this->manager->flush();
            $this->alert->success("Le Commentaire a bien été mis à jour");

            return $this->redirectToRoute('show_comments');
        }

        return $this->render('admin/comments/edit_comment.html.twig', [
            'commentEditForm' => $form->createView()
        ]);
    }

    #[Route('/admin/comment/{id}/delete/', name: 'delete_comment')]
    /**
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteComment(Comment $comment)
    {
        $this->manager->remove($comment);
        $this->manager->flush();
        $this->alert->success("Le Commentaire a été supprimé");
        return $this->redirectToRoute('show_comments');
    }
}
