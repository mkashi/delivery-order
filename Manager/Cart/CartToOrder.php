<?php

namespace Delivery\OrderBundle\Manager\Cart;

use Delivery\ApiBundle\Entity\Order\OrderLine;
use Delivery\ApiBundle\Repository\ProductRepository;

/**
 * Class CartToOrder
 */
class CartToOrder
{
    /**
     * Todo update interface and replace here.
     *
     * @var CartManagerSession
     */
    private $cartManager;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @param CartManagerSession $cartManagement
     * @param ProductRepository $productRepository
     */
    public function __construct(CartManagerSession $cartManagement, ProductRepository $productRepository)
    {
        $this->cartManager = $cartManagement;
        $this->productRepository = $productRepository;
    }

    /**
     * @return OrderLine[]
     */
    public function createLinesFromCart()
    {
        $lines = [];

        foreach ($this->cartManager->getCart() as $cartItem) {
            $line = new OrderLine();

            $line->setQuantity($cartItem->getQuantity());
            $line->setTotal($cartItem->getTotal());
            $line->setProduct($this->productRepository->find($cartItem->getProduct()->getId()));

            $lines[] = $line;
        }

        return $lines;
    }
}