<?php

namespace Delivery\OrderBundle\Controller;

use Delivery\ApiBundle\Entity\Localisation\Department;
use Delivery\ApiBundle\Manager\CategoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class DepartmentController
 */
class DepartmentController extends Controller
{
    /**
     * @ParamConverter("department", options={"mapping": {"slug": "slug"}})
     *
     * @Route("/restaurant-livraison/commander-en-ligne/{slug}/department", name="department_order")
     *
     * @param Department      $department
     * @param CategoryManager $categoryManager
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function departmentAction(Department $department, CategoryManager $categoryManager)
    {
        $categories = $categoryManager->getPublishedCategoriesAndProducts();

        return $this->render('@DeliveryOrder/department/order.html.twig', [
            'categories' => $categories,
            'department' => $department,
        ]);
    }
}
