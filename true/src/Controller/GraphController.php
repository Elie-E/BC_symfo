<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class GraphController extends AbstractController
{
    private $productRepository;
    private $categoryRepository;
    private $orderRepository;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository, OrderRepository $orderRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @Route("/api/graph/1", "graph_1", methods={"GET", "POST"})
     */
    public function getFirstGraph(): Response
    {

        $encoder = [new XmlEncoder(), new JsonEncoder()];
        $normalizer = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizer, $encoder);

        $array =[];

        $categories = $this->categoryRepository->findAll();
        foreach($categories as $category){

            array_push($array,
                ['name'=> $category->getCategoryName(),
                'value'=> count($this->productRepository->findBy(['category' => $category]))
                ]
            );         
        }

        return new JsonResponse($serializer->serialize($array, 'json'));
    }
}
