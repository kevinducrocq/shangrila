<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangeAddressType;
use App\Service\Cart\CartServiceInterface;
use App\Service\Alert\AlertServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

    private CartServiceInterface $cartService;
    public function __construct(CartServiceInterface $cartService)
    {
        $this->cartService = $cartService;
    }

    #[Route('/cart', name: 'cart')]
    /**
     *  @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $this->cartService->getFullCart(),
            'total' =>  $this->cartService->getTotal(),
            'order' =>  $this->getOrderDetails(),
        ]);
    }

    public function getOrderDetails(): array
    {
        $detailCart = $this->cartService->getDetailCart();
        $delivery = [];

        if ($detailCart) {
            $delivery = $detailCart;
        } else {
            $delivery = [
                'address' => $this->getUser()->getAddress(),
                'zipcode' => $this->getUser()->getZipcode(),
                'city' => $this->getUser()->getCity()
            ];
        }
        return $delivery;
    }

    #[Route('/add/{id}', name: 'addItemToCart')]
    public function addItemToCart($id, AlertServiceInterface $alert)
    {
        $this->cartService->addItemToCart($id);
        $alert->success('Produit ajouté');
        return $this->redirectToRoute("cart");
    }

    #[Route('/remove/{id}', name: 'removeItemFromCart')]
    public function removeItemFromCart($id, AlertServiceInterface $alert)
    {
        $this->cartService->removeItemFromCart($id);
        $alert->warning('Produit retiré');
        return $this->redirectToRoute("cart");
    }

    #[Route('/delete/{id}', name: 'deleteFromCart')]
    public function deleteLineFromCart($id, AlertServiceInterface $alert)
    {
        $this->cartService->deleteLineFromCart($id);
        $alert->danger('Produit supprimé');
        return $this->redirectToRoute("cart");
    }

    #[Route('/delete', name: 'emptyCart')]
    public function emptyCart()
    {
        $this->cartService->emptyCart();
        return $this->redirectToRoute("cart");
    }

    #[Route('/change-address', name: 'change_address')]
    public function changeAddress(Request $request, AlertServiceInterface $alert)
    {
        //   
        $form = $this->createForm(ChangeAddressType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = [
                'address' => $form->get('address')->getData(),
                'zipcode' => $form->get('zipcode')->getData(),
                'city' => $form->get('city')->getData()
            ];

            $this->cartService->setDetailCart($data);
            $alert->success('votre adresse de livraison a été changée');

            return $this->redirectToRoute('cart');
        }
        return $this->render('order/_change_address.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
