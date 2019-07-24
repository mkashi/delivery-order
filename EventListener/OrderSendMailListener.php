<?php

namespace Delivery\OrderBundle\EventListener;

use Delivery\ApiBundle\Event\OrderEvent;
use Delivery\OrderBundle\Mailer\Mailer;
use Symfony\Bridge\Twig\TwigEngine;

/**
 * Class OrderSendMailListener
 */
class OrderSendMailListener
{
    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * OrderSendMailListener constructor.
     *
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param OrderEvent $orderEvent
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function onNewOrder(OrderEvent $orderEvent)
    {
        $order = $orderEvent->getOrder();

        $this->mailer->sendOrderEmail($order);
    }
}