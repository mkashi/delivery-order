<?php

namespace Delivery\OrderBundle\Controller;

use Delivery\ApiBundle\Entity\Product;
use Delivery\ApiBundle\Manager\CategoryManager;
use Delivery\ApiBundle\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Delivery\ApiBundle\Entity\Category;


/**
 * Class ProductController
 */
class ProductController extends Controller
{
    /**
     * @ParamConverter("product", options={"mapping": {"slug": "slug"}})
     *
     * @Route("/livraison-nuit/produit/{slug}", name="product_get")
     *
     * @param Product $product
     * @param CategoryManager $categoryManager
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getProductAction(Product $product, CategoryManager $categoryManager)
    {
        $categories = $categoryManager->getPublishedCategoriesAndProducts();

        return $this->render('@DeliveryOrder/product/get.html.twig', [
            'categories' => $categories,
            'product' => $product,
        ]);
    }


    /**
     * @Route("/livraison-nuit/produits", name="product_list")
     *
     * @param ProductRepository $productRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getProductListAction(ProductRepository $productRepository, CategoryManager $categoryManager)
    {
        $categories = $categoryManager->getPublishedCategoriesAndProducts();

        return $this->render('@DeliveryOrder/product/list.html.twig', [
            'categories' => $categories,
        ]);
    }
}
