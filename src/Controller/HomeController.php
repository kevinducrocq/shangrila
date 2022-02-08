<?php

namespace App\Controller;

use App\Entity\Food;
use App\Entity\Comment;
use App\Entity\Contact;
use App\Form\CommentFormType;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\Alert\AlertServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $food = $doctrine->getRepository(Food::class)->findAll();
        $comments = $doctrine->getRepository(Comment::class)->findAll();
        return $this->render('/home.html.twig', [
            "food" => $food,
            "comments" => $comments
        ]);
    }

    #[Route('/comment', name: 'add_comment')]
    /**
     * @IsGranted("ROLE_USER")
     */
    public function comment(EntityManagerInterface $manager, Request $request, AlertServiceInterface $alert)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setStatus(0);
            $manager->persist($comment);
            $manager->flush();
            $alert->success('Merci pour votre commentaire');
            return $this->redirectToRoute('home');
        }

        return $this->render('users/_add_comment.html.twig', [
            'commentForm' => $form->createView(),
        ]);
    }

    #[Route('/contact', name: 'contact')]
    /**
     * @IsGranted("ROLE_USER")
     */
    public function contact(EntityManagerInterface $manager, AlertServiceInterface $alert, Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setUser($this->getUser());
            $contact->setStatus(0);
            $manager->persist($contact);
            $manager->flush();
            $alert->success('Votre message a été envoyé');
            return $this->redirectToRoute('home');
        }

        return $this->render('/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
