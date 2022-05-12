<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Form\AddToCartType;
use App\Form\CartType;
use App\Form\ProductImageType;
use App\Form\ProductType;
use App\Form\SearchType;
use App\Repository\CategoryRepository;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        if($this->getUser() && in_array("ROLE_ADMIN", $this->getUser()->getRoles()))
        {
            return $this->render('product/index.html.twig', [
                'products' => $productRepository->findAll(),
            ]);
        }
    }

    /**
     * @Route("/list/{id}", name="product_list", methods={"GET"})
     */
    public function list(ProductRepository $productRepository, CategoryRepository $categoryRepository, $id): Response
    {
        $category = $categoryRepository->find($id);

        return $this->render('product/list.html.twig', [
            'category' => $category,
            'products' => $category->getProducts(),
        ]);
    }
// Sans sensio framework :
    /**
     * @Route("/list/{id}/lower", name="product_list_lower", methods={"GET"})
     */
    public function listByLowerPrice(ProductRepository $productRepository, CategoryRepository $categoryRepository, $id): Response
    {
        $category = $categoryRepository->find($id);
        $products = $productRepository->findBy(['category' => $category], ['price_ht' => 'ASC']);

        return $this->render('product/list.html.twig', [
            'category' => $category,
            'products' => $products
        ]);
    }
// Avec sensio framework :
    /**
     * @Route("/list/{category}/higher", name="product_list_higher", methods={"GET"})
     */
    public function listByHigherPrice(ProductRepository $productRepository, Category $category): Response
    {
        $products = $productRepository->findBy(['category' => $category], ['price_ht' => 'DESC']);

        // $category = $categoryRepository->find($id);
        // $products = $productRepository->findBy(['category' => $category], ['price_ht' => 'DESC']);

        return $this->render('product/list.html.twig', [
            'category' => $category,
            'products' => $products
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $product = new Product();
        

        $image = new ProductImage();
        $product->getProductImages()->add($image);

        $image2 = new ProductImage();
        $product->getProductImages()->add($image2);
      
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageArray=[];
            $entityManager = $this->getDoctrine()->getManager();

            $imageCollection = $form->get('productImages');

            foreach($imageCollection as $collection){
                
                $imageData = $collection->get('image_product')->getData();

                if($imageData) {
                    $originalFileName = pathinfo($imageData->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFileName = $slugger->slug($originalFileName);
                    $newFileName = $safeFileName.'-'.uniqid().'-'.$imageData->guessExtension();
                    
                    try {
                        $imageData->move(
                            $this->getParameter('image_directory'),
                            $newFileName
                        );
                    } catch (FileException $e) {}
                    $imageArray[] = $newFileName;
                }
            }
            $image = $product->getProductImages();

            for($i=0; $i<count($imageArray); $i++){
                $image[$i]->setUrlImage($imageArray[$i]);
                $image[$i]->setProduct($product);

                $entityManager->persist($image[$i]);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product, Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        
        if($this->getUser() && in_array("ROLE_ADMIN", $this->getUser()->getRoles()))
        {

            return $this->render('product/show.html.twig', [
                'product' => $product,
            ]);
        } 

            $cartForm = $this->createForm(AddToCartType::class);
            $cartForm->handleRequest($request);
            
            // if ($cartForm->isSubmitted() && $cartForm->isValid()) {
                
            // }
    
            return $this->render('product/detail.html.twig', [
                'cartForm' => $cartForm->createView(),
                'product' => $product,
            ]);  
        
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product, SluggerInterface $slugger): Response
    {
        if(!$product->getProductImages()){
            $image = new ProductImage();
            $product->getProductImages($image);
            $image2 = new ProductImage();
            $product->getProductImages($image2);
        }
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageArray=[];
            $entityManager = $this->getDoctrine()->getManager();

            $imageCollection = $form->get('productImages');

            foreach($imageCollection as $collection){
                
                $imageData = $collection->get('image_product')->getData();

                if($imageData) {
                    $originalFileName = pathinfo($imageData->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFileName = $slugger->slug($originalFileName);
                    $newFileName = $safeFileName.'-'.uniqid().'-'.$imageData->guessExtension();
                    
                    try {
                        $imageData->move(
                            $this->getParameter('image_directory'),
                            $newFileName
                        );
                    } catch (FileException $e) {}
                    $imageArray[] = $newFileName;
                }
            }
            $image = $product->getProductImages();

            for($i=0; $i<count($imageArray); $i++){
                $image[$i]->setUrlImage($imageArray[$i]);
                $image[$i]->setProduct($product);

                $entityManager->persist($image[$i]);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product): Response
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        
        $images = $product->getProductImages();
        if($images){
            foreach($images as $image){
                
                $entityManager->remove($image);
            }
        }

        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {

            // $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
    }
}
