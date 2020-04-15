<?php

namespace Delivery\OrderBundle\Controller;

use Delivery\ApiBundle\Entity\Localisation\City;
use Delivery\ApiBundle\Manager\CategoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Delivery\ApiBundle\Entity\Category;


/**
 * Class CityController
 */
class CityController extends Controller
{
    /**
     * @ParamConverter("city", options={"mapping": {"slug": "slug"}})
     *
     * @Route("/livraison-nuit/{department}/ville/{slug}", name="city_order")
     *
     * @param string $department
     * @param City $city
     * @param CategoryManager $categoryManager
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function cityAction($department, City $city, CategoryManager $categoryManager)
    {
        $categories = $categoryManager->getPublishedCategoriesAndProducts();

        return $this->render('@DeliveryOrder/city/order.html.twig', [
            'categories' => $categories,
            'city' => $city,
            'department' => $city->getDepartment(),
        ]);
    }


    /**
     * @ParamConverter("city", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("category", options={"mapping": {"categorySlug": "slug"}})
     *
     * @Route("/livraison-nuit/{categorySlug}/{department}/{slug}", name="city_category_order")
     *
     * @param Category $category
     * @param string $department
     * @param City $city
     * @param CategoryManager $categoryManager
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function cityCategoryAction(Category $category, $department, City $city, CategoryManager $categoryManager)
    {
        return $this->render('@DeliveryOrder/city/category_order.html.twig', [
            'categories' => $categoryManager->getPublishedCategoriesAndProducts(),
            'category' => $category,
            'city' => $city,
            'department' => $city->getDepartment(),
        ]);
    }
}
