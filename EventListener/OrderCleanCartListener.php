<?php

namespace Delivery\OrderBundle\EventListener;

use Delivery\ApiBundle\Event\OrderEvent;
use Delivery\OrderBundle\Mailer\Mailer;
use Delivery\OrderBundle\Manager\Cart\CartManagerSession;

/**
 * Class OrderSendMailListener
 */
class OrderCleanCartListener
{
    /**
     * @var Mailer
     */
    private $cart;


    /**
     * OrderCleanCartListener constructor.
     * @param CartManagerSession $cartSession
     */
    public function __construct(CartManagerSession $cartSession)
    {
        $this->cart = $cartSession;
    }

    /**
     * @param OrderEvent $orderEvent
     */
    public function onNewOrder(OrderEvent $orderEvent)
    {
        $this->cart->clear();
    }
}