<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Product;
use App\Form\SearchType;
use App\Repository\CategoryRepository;
use App\Repository\ClientRepository;
use App\Repository\ProductRepository;
use SebastianBergmann\Type\VoidType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    /**
     * @Route("/", name="home_page")
     */
    public function index(CategoryRepository $categoryRepository, ProductRepository $productRepository, Request $request): Response
    {

        return $this->render('home_page/homepage.html.twig', [
            'controller_name' => 'HomePageController',
            'products' => $productRepository->findAll(),
        ]);
    }

        /**
     * @Route("/panier/add/{id}", name="cart_add")
     * @param $id
     * @param SessionInterface $session
     */
    public function add($id, SessionInterface $session) {

        $panier=$session->get('panier',[]);
        if (!empty($panier[$id])){
            $panier[$id]++;
        }
        else{
            $panier[$id]=1;
        }
        $session->set('panier',$panier);
        return $this->redirectToRoute('home_page');
    }


    /**
     * @Route("/panier/remove/{id}", name="remove_cart")
     * @param $id
     * @param SessionInterface $session
     */
    public function remove ($id, SessionInterface $session) {

        $panier=$session->get('panier',[]);
        if (!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier', $panier);
        return $this->redirectToRoute('home_page');
    }

// Cette fonction sert à afficher la nav sur toutes les pages, elle est rendered dans le header lui même inclus dans base-site.

    public function displayNavigation(CategoryRepository $categoryRepository, ProductRepository $productRepository, Request $request)
    {
        $session = $this->requestStack->getSession();

        $panier=$session->get('panier',[]);

        $panierWithDAta=[];
        foreach ($panier as $id=>$quantity){

            $panierWithDAta[]=[
              'product'=>$productRepository->find($id),
                'quantity'=>$quantity
            ];
        }
        $total=0;
        foreach ($panierWithDAta as $item){
            $totalitem=($item['product']->getPriceHt()*$item['product']->getTva())* $item['quantity'];
            $total +=$totalitem;
        }

// [     Si j'avais voulu intégrer le traitement fait plus bas ( dans searchBar() ) je l'aurai mit ici ...       ]  

// On précise l'action du formulaire :
        $searchForm = $this->createForm(SearchType::class, null, [
            'action' => '/search',           
        ]);
        
        return $this->render('elements/_navigation.html.twig', [
            'controller_name' => 'HomePageController',
            'searchForm' => $searchForm->createView(),
            'categories' => $categoryRepository->findAll(),
            'products' => $productRepository->findAll(),

            'items'=>$panierWithDAta,
            'total'=>$total,
        ]);
    }

// Ici j'ai choisit de déporté la fonction qui soumet et traite le formulaire de la barre de recherche, j'aurai pu intégrer tout ce traitement
// dans la méthode displayNavigation() au dessus, et ajouter la @Route "search" à displlayNavigation() comme ça la fonction s'apelle elle même
// J'ai déporté simplement pour la compréhension du code
    /**
     * @Route("search", name="search", methods={"POST"})
     */
    public function searchBar(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository){

    
        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            $filtres = $searchForm->getData();
            $productsSearched = $productRepository->search($filtres);

            return $this->render('elements/search-result.html.twig', [
                'searchForm' => $searchForm->createView(),
                'productsSearched' => $productsSearched,
                'filtres' => $filtres,
                // 'categories' => $categoryRepository->findAll(),
                // 'products' => $productRepository->findAll(),
            ]);
        }
    }
}
