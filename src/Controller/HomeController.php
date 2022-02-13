<?php

namespace App\Controller;

use App\Entity\Food;
use App\Entity\Comment;
use App\Entity\Contact;
use App\Form\CommentFormType;
use App\Form\ContactFormType;
use App\Repository\CommentRepository;
use App\Repository\FoodRepository;
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

    private AlertServiceInterface $alert;
    private EntityManagerInterface $manager;
    public function __construct(AlertServiceInterface $alert, EntityManagerInterface $manager)
    {
        $this->alert = $alert;
        $this->manager = $manager;
    }

    #[Route('/', name: 'home')]
    public function index(FoodRepository $foodRepository, CommentRepository $commentRepository): Response
    {
        $food = $foodRepository->findAll();
        $comments = $commentRepository->findBy(array('status' => 1));
        return $this->render('/home.html.twig', [
            "food" => $food,
            "comments" => $comments
        ]);
    }

    #[Route('/comment', name: 'add_comment')]
    /**
     * @IsGranted("ROLE_USER")
     */
    public function comment(Request $request)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setStatus(0);
            $this->manager->persist($comment);
            $this->manager->flush();
            $this->alert->success('Merci pour votre commentaire');
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
    public function contact(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setUser($this->getUser());
            $contact->setStatus(0);
            $this->manager->persist($contact);
            $this->manager->flush();
            $this->alert->success('Votre message a été envoyé');
            return $this->redirectToRoute('home');
        }

        return $this->render('/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
