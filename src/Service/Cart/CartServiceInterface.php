<?php

namespace App\Service\Cart;

interface CartServiceInterface
{
    public function addItemToCart(int $id);

    public function removeItemFromCart(int $id);

    public function deleteLineFromCart(int $id);

    public function emptyCart();

    public function getTotal(): float;

    public function getFullCart(): array;

    public function getDetailCart();

    public function setDetailCart($detail = []);

    public function removeDetailCart();
}
