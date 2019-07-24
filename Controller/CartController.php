<?php

namespace Delivery\OrderBundle\Controller;

use Delivery\ApiBundle\Entity\Product;
use Delivery\OrderBundle\Manager\Cart\CartManagerSession;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CartController
 */
class CartController extends Controller
{
    /**
     * @Route("/cart/add/{id}", name="add_product")
     *
     * @param Product $product
     *
     * @return Response
     */
    public function addToCart(Product $product, CartManagerSession $cartManager)
    {
        $cartManager->addProduct($product);

        return $this->json([
            'status' => 1,
            'cart_html' => $this->renderCartHtml($cartManager, false),
            'nb_item' => $cartManager->getNbItem(),
        ]);

    }

    /**
     * @Route("/cart/remove/{id}", name="remove_item")
     *
     * @return Response
     */
    public function removeFromCart(Product $product, CartManagerSession $cartManager)
    {
        $cartManager->remove($product);

        return $this->json([
            'status' => '1',
            'cart_html' => $this->renderCartHtml($cartManager, true),
            'nb_item' => $cartManager->getNbItem(),
        ]);
    }

    /**
     * @Route("cart/delete/{id}",name="delete_item")
     *
     * @param Product $product
     *
     * @return JsonResponse
     */
    public function deleteProduct(Product $product, CartManagerSession $cartManager)
    {
        $cartManager->delete($product);

        return $this->json([
            'status' => 1,
            'cart_html' => $this->renderCartHtml($cartManager),
            'nb_item' => $cartManager->getNbItem(),
        ]);
    }

    /**
     * @return Response
     */
    public function renderCart(CartManagerSession $cartManager)
    {
        return new Response($this->renderCartHtml($cartManager));
    }

    /**
     * @return string
     */
    private function renderCartHtml(CartManagerSession $cartManager, $isOpen = false)
    {
        $cart = $cartManager->getItems();
        $total = $cartManager->getTotal();
        $nbItem = $cartManager->getNbItem();

        return $this->renderView('@DeliveryOrder/cart/cart_show.html.twig', [
            'cart_items' => $cart,
            'total' => $total,
            'nb_item' => $nbItem,
            'is_open' => $isOpen,
        ]);
    }
}
