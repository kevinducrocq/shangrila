<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Entity\OrderItems;
use Stripe\Checkout\Session;
use Symfony\Component\Mime\Email;
use App\Controller\CartController;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Cart\CartServiceInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    #[Route('/checkout', name: 'checkout')]
    public function checkout($stripeSK, EntityManagerInterface $manager, CartServiceInterface $cartService, CartController $cartController)
    {
        $totalAccount = $cartService->getTotal() * 100;
        // Création d'une commande
        $order = new Order();
        $order->setTotalAccount($totalAccount);
        $order->setUser($this->getUser());
        $order->setStatus(false);

        // Récupération des articles en session
        $items = $cartService->getFullCart();
        $nbProduct = 0;

        foreach ($items as $item) {
            $itemObject = new OrderItems();
            $itemObject->setQuantity($item['quantity']);
            $itemObject->setFood($item['food']);
            $nbProduct = $nbProduct + $item['quantity'];
            $order->addItem($itemObject);
        }

        $order->setNbProducts($nbProduct);
        $delivery = $cartController->getOrderDetails();

        $order->setAddress($delivery['address']);
        $order->setZipcode($delivery['zipcode']);
        $order->setCity($delivery['city']);

        $manager->persist($order);
        $manager->flush();

        Stripe::setApiKey($stripeSK);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Votre panier pour Shangrila'
                    ],
                    'unit_amount' => $totalAccount,
                ],
                'quantity' => '1',
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success', ['id' => $order->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('error', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($session->url, 303);
    }

    #[Route('/success/{id}', name: 'success')]
    public function success(Order $order, EntityManagerInterface $manager, CartServiceInterface $cartService, MailerInterface $mailer)
    {
        $cartService->emptyCart();
        $cartService->removeDetailCart();

        $order->setStatus(true);

        $user = $order->getUser();

        $email = (new TemplatedEmail())
            ->from(new Address('kducrocq.dev@gmail.com', 'Admin Shangrila'))
            ->to($user->getEmail())
            ->subject('Votre commande N°' . $order->getId() . '')
            ->htmlTemplate('order/email.html.twig', ['order' => $order]);

        $mailer->send($email);

        $manager->persist($order);
        $manager->flush();


        return $this->render('cart/success.html.twig');
    }

    #[Route('/error', name: 'error')]
    public function error()
    {
        return $this->render('cart/error.html.twig', []);
    }
}
