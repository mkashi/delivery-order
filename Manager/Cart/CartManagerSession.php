<?php

namespace Delivery\OrderBundle\Manager\Cart;

use Delivery\ApiBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class CartManagerSession
 *
 * @TODO SEE FOR REAL INTERFACE
 */
class CartManagerSession
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * CartSession constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param Product $product
     */
    public function addProduct(Product $product)
    {
        $index = $this->getIndex($product);
        $cart = $this->getCart();

        if ($index == -1) {
            $cart[]= new CartItem($product);
        } elseif ($index >= 0) {
            $cart[$index]->setQuantity($cart[$index]->getQuantity()+1);
        }

        $this->session->set('cart', $cart);
    }

    /**
     * @param Product $product
     */
    public function remove(Product $product)
    {
        $index = $this->getIndex($product);

        if ($index >= 0) {
            if (1 == $this->getQuantity($product)) {
                $this->delete($product);
            } elseif ($this->getQuantity($product)>1) {
                $this->setQuantity($product,$this->getQuantity($product) - 1);
            }
        }
    }

    /**
     * @param Product $product
     *
     * @return int
     */
    public function delete(Product $product){
        $cart = $this->getCart();
        $index = $this->getIndex($product);

        unset($cart[$index]);
        $this->session->set('cart',$cart);
        return 1;
    }

    /**
     * @param Product $product
     *
     * @return integer
     */
    public function getQuantity(Product $product)
    {
        $cart = $this->getCart();
        $index = $this->getIndex($product);

        return $cart[$index]->getQuantity();
    }

    /**
     * @param Product $product
     * @param int     $quantity
     */
    public function setQuantity(Product $product, $quantity)
    {
        $cart = $this->getCart();

        $index = $this->getIndex($product);

        $cart[$index]->setQuantity($quantity);

        $this->session->set('cart', $cart);
    }

    /**
     * Alias getCart.
     *
     * @return CartItem[]
     */
    public function getItems()
    {
        return $this->getCart();
    }

    /**
     * Clear all the cart session
     */
    public function clear()
    {
        $this->session->set('cart', []);
    }

    /**
     * Return -1 if product is not in the cart.
     *
     * @param Product $product
     *
     * @return int
     */
    public function getIndex(Product $product)
    {
        $cart = $this->getCart();

        foreach ((array)$cart as $key => $value) {
            if ($product->equals($value->getProduct())) {
                return $key;
            }
        }

        return -1;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        $total = 0;
        $cart = $this->getCart();

        foreach ($cart as $item) {
            $total += $item->getTotal();
        }

        return $total;
    }

    /**
     * Nb item in the cart.
     *
     * @return int
     */
    public function getNbItem()
    {
        $nbItem = 0;
        $cart = $this->getCart();

        foreach ($cart as $cartItem) {
            $nbItem += $cartItem->getQuantity();
        }

        return $nbItem;
    }

    /**
     * @return CartItem[]
     */
    public function getCart()
    {
        return $this->session->get('cart', []);
    }
}