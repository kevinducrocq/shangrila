<?php

namespace App\Twig;

use App\Entity\Food;
use Twig\TwigFunction;
use App\Entity\Comment;
use App\Entity\Contact;
use Twig\Extension\AbstractExtension;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\Cart\CartServiceInterface;

class AppExtension extends AbstractExtension
{
    public function __construct(CartServiceInterface $cartServiceInterface, ManagerRegistry $managerRegistry)
    {
        $this->cartServiceInterface = $cartServiceInterface;
        $this->managerRegistry = $managerRegistry;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('NumberItemsInCart', [$this, 'itemsInCart']),
            new TwigFunction('commentNotif', [$this, 'notifComment']),
            new TwigFunction('messageNotif', [$this, 'notifMessage']),
        ];
    }

    public function itemsInCart()
    {
        if ($items = $this->cartServiceInterface->getFullCart()) {
            $nbProduct = 0;
            foreach ($items as $item) {
                $nbProduct = $nbProduct + $item['quantity'];
            }
            return $nbProduct;
        } else {
            return null;
        }
    }

    public function notifComment()
    {
        $newComments = $this->managerRegistry->getRepository(Comment::class)->findBy(['status' => 0]);
        if ($newComments) {
            return true;
        } else {
            return false;
        }
    }

    public function notifMessage()
    {
        $newMessages = $this->managerRegistry->getRepository(Contact::class)->findBy(['status' => 0]);
        if ($newMessages) {
            return true;
        } else {
            return false;
        }
    }
}
