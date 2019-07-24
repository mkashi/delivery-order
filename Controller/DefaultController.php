<?php

namespace Delivery\OrderBundle\Controller;

use Delivery\ApiBundle\Manager\CategoryManager;
use Delivery\ApiBundle\Repository\DepartmentRepository;
use Delivery\OrderBundle\Domain\ContactDomain;
use Delivery\OrderBundle\Form\Order\ContactType;
use Delivery\OrderBundle\Mailer\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @return Response
     */
    public function homeAction(CategoryManager $categoryManager)
    {
        $categories = $categoryManager->getPublishedCategoriesAndProducts();

        return $this->render('@DeliveryOrder/Default/homepage.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/restaurant-livraison/commander-en-ligne", name="order")
     *
     * @return Response
     */
    public function menuAction(CategoryManager $categoryManager, DepartmentRepository $departmentRepository)
    {
        $categories = $categoryManager->getPublishedCategoriesAndProducts();

        return $this->render('@DeliveryOrder/Default/order.html.twig', [
            'categories' => $categories,
            'departments' => $departmentRepository->findBy([], ['zip' => 'asc']),
        ]);
    }

    /**
     * @Route("/nous-contacter", name="contact")
     */
    public function contactAction()
    {
        $form = $this->createForm(ContactType::class);

        return $this->render('@DeliveryOrder/Default/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/contact/send", methods={"POST"})
     *
     * @param Request $request
     * @param Mailer  $mailer
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendContact(Request $request, Mailer $mailer)
    {
        $contact = new ContactDomain();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $mailer->sendContactEmail($contact);

            return $this->json(['status' => 0]);
        }

        return $this->json(['status' -1], 400);
    }
}
