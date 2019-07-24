<?php

namespace Delivery\OrderBundle\Controller;

use Delivery\ApiBundle\Entity\Localisation\Department;
use Delivery\ApiBundle\Entity\Order\Order;
use Delivery\ApiBundle\Manager\OrderManager;
use Delivery\OrderBundle\Form\Order\CreateOrderType;
use Delivery\OrderBundle\Manager\Cart\CartManagerSession;
use Delivery\OrderBundle\Manager\Cart\CartToOrder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrderController
 */
class OrderController extends Controller
{
    /**
     * @Route("/order/", name="new_order", methods={"POST"})
     *
     *
     * @param Request $request
     * @param OrderManager $orderManager
     * @param CartToOrder $cartToOrder
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Request $request, OrderManager $orderManager, CartToOrder $cartToOrder)
    {
        $order = new Order();
        $form = $this->createForm(CreateOrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $order->setLines($cartToOrder->createLinesFromCart());

            if ($order->getTotal() < 15) {
                return $this->json([
                    'status' => -1,
                    'error' => 'Le minimum de commande est de 15€',
                ], 400);
            }

            $orderManager->createOrder($order);

            return $this->json([
                'status' => 0,
                'html' => $this->renderValidateView($order),
            ], 200);
        }

        return $this->json($form->getErrors(), 400);
    }

    /**
     * @Route("/order/render", name="render_order")
     */
    public function modalCreate(CartManagerSession $cartManager)
    {
        $form = $this->createForm(CreateOrderType::class);

        return $this->render('@DeliveryOrder/order/order_modal.html.twig', [
            'cart' => $cartManager,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return string html
     */
    public function renderValidateView(Order $order)
    {
        return $this->renderView('@DeliveryOrder/order/order_modal_validate.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/departments/{id}/cities", name="department_cities")
     *
     * @param Department $department
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getCitiesOfDepartement(Department $department)
    {
        return $this->json($department->getCities());
    }
}
