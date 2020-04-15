<?php

namespace Delivery\OrderBundle\Mailer;

use Delivery\ApiBundle\Entity\Order\Order;
use Delivery\OrderBundle\Domain\ContactDomain;
use Twig\Environment;

/**
 * Class Mailer
 */
class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $templating;

    /**
     * @var string
     */
    private $emailFrom;

    /**
     * @var array
     */
    private $emailContactTo;

    /**
     * @var array
     */
    private $emailOrderTo;

    /**
     * @var string
     */
    private $website;

    /**
     * Mailer constructor.
     *
     * @param \Swift_Mailer     $mailer
     * @param Environment $templating
     */
    public function __construct(\Swift_Mailer $mailer, Environment $templating, $website)
    {
        $this->website = $website;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param ContactDomain $contact
     */
    public function sendContactEmail(ContactDomain $contact)
    {
        $message = new \Swift_Message();

        $message->setFrom($this->emailFrom);
        $message->setTo($this->emailContactTo);
        $message->setSubject(sprintf('[%s] Contact: %s', $this->website, $contact->getSubject()));

        $html = $this->templating->render('@DeliveryOrder/mail/mail_contact.html.twig', [
            'contact' => $contact,
        ]);

        $message->setBody($html);

        $this->mailer->send($message);
    }

    /**
     * @param Order $order
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendOrderEmail(Order $order)
    {
        $message = new \Swift_Message();

        $message->setFrom($this->emailFrom);
        $message->setTo($this->emailOrderTo);
        $message->setSubject(sprintf('[%s] Commande #%s à: %s', $this->website, $order->getId(), $order->getAddress()->getCity()));

        $html = $this->templating->render('@DeliveryOrder/mail/mail_order.html.twig', [
            'order' => $order,
        ]);

        $message->setBody($html);

        $this->mailer->send($message);
    }


    /**
     * @param string $emailFrom
     */
    public function setEmailFrom($emailFrom)
    {
        $this->emailFrom = $emailFrom;
    }

    /**
     * @param array $emailContactTo
     */
    public function setEmailContactTo($emailContactTo)
    {
        $this->emailContactTo = $emailContactTo;
    }

    /**
     * @param array $emailOrderTo
     */
    public function setEmailOrderTo($emailOrderTo)
    {
        $this->emailOrderTo = $emailOrderTo;
    }
}
