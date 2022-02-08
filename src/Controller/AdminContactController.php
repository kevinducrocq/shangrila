<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\AnswerFormType;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\Alert\AlertServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminContactController extends AbstractController
{
    #[Route('/admin/contact', name: 'show_messages')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $messages = $doctrine->getRepository(Contact::class)->findAll();
        return $this->render('admin/contact/show_messages.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/admin/contact/reply/{id}', name: 'reply_message')]
    public function reply(Contact $contact, Request $request, EntityManagerInterface $manager, AlertServiceInterface $alert, MailerInterface $mailer)
    {
        $user = $contact->getUser();
        $form = $this->createForm(AnswerFormType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setStatus(1);
            $manager->persist($contact);
            $manager->flush();

            $email = (new Email())
                ->from('kducrocq.dev@gmail.com')
                ->to($user->getEmail())
                ->subject('Réponse à votre message')
                ->html('<p> Votre message : <br>' . $contact->getMessage() . '</p><br><hr><p> Réponse : <br>' . $contact->getAnswer() . '</p>');
            $mailer->send($email);
            $alert->success("Votre réponse a bien été envoyée");
            return $this->redirectToRoute('show_messages');
        }
        return $this->render('admin/contact/reply_message.html.twig', [
            'contactAnswerForm' => $form->createView(),
        ]);
    }
}
