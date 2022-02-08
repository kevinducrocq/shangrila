<?php

namespace App\Service\Cart;

use App\Repository\FoodRepository;
use Symfony\Component\HttpFoundation\Session\Session;

class CartService implements CartServiceInterface
{
    protected $foodRepository;
    public function __construct(FoodRepository $foodRepository)
    {
        $this->session = new Session();
        $this->foodRepository = $foodRepository;
    }

    public function addItemToCart(int $id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function removeItemFromCart(int $id)
    {
        // On récupère le panier actuel
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }

        // On sauvegarde dans la session
        $this->session->set('cart', $cart);
    }

    public function deleteLineFromCart(int $id)
    {

        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }

        $this->session->set('cart', $cart);
    }

    public function emptyCart()
    {
        $this->session->remove('cart', []);
    }

    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getFullCart() as $item) {

            $total += $item['food']->getPrice() * $item['quantity'];

        }

        return $total;
    }

    public function getFullCart(): array
    {
        $cart = $this->session->get('cart', []);
        $dataCart = [];

        foreach ($cart as $id => $quantity) {
            $food = $this->foodRepository->find($id);
            $dataCart[] = [
                "food" => $food,
                "quantity" => $quantity,
            ];
        }

        return $dataCart;
    }

    public function getDetailCart()
    {
        return $this->session->get('detailCart');
    }

    public function setDetailCart($detail = [])
    {
        $this->session->set('detailCart', $detail);
    }

    public function removeDetailCart()
    {
        $this->session->remove('detailCart', []);
    }
}
