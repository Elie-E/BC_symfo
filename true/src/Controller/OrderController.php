<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderLog;
use App\Form\OrderType;
use App\Repository\OrderLogRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\StateRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/", name="order_index", methods={"GET"})
     */
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('order/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="order_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order/new.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="order_show", methods={"GET"})
     */
    public function show(Order $order, OrderLogRepository $orderLogRepository): Response
    {
        $orderProductList = $orderLogRepository->findBy(['orderNumber' => $order]);
        
        return $this->render('order/show.html.twig', [
            'order' => $order,
            'list' => $orderProductList
        ]);
    }

    /**
     * @Route("/{id}/edit", name="order_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Order $order): Response
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('order/edit.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="order_delete", methods={"POST"})
     */
    public function delete(Request $request, Order $order): Response
    {
        if ($this->isCsrfTokenValid('delete'.$order->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($order);
            $entityManager->flush();
        }

        return $this->redirectToRoute('order_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/validation/new", name="order_validation")
     */
    public function clientNewOrder(ProductRepository $productRepository, Request $request, RequestStack $requestStack, StateRepository $stateRepository, OrderLogRepository $orderLogRepository): Response
    {
        // First I check if the user already has an account :
            if($this->getUser()){
            
            // Getting session's objects to put it in a table to display :
            $session = $requestStack->getSession();
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
            $totalitem=($item['product']->getPriceHt() * $item['product']->getTva())* $item['quantity'];
            $total +=$totalitem;
        }
        
        // Create a new Order Object
        $clientOrder = new Order;
        
        // Creating the form but I will only render the payment method field ( in the template ):
            $orderForm = $this->createForm(OrderType::class, $clientOrder);
            $orderForm->handleRequest($request);
            
            if ($orderForm->isSubmitted()){
            $entityManager = $this->getDoctrine()->getManager();

            $clientOrder->setOrderNumber(time().'_'.uniqid(true));
            $clientOrder->setOrderDate(new DateTime());
            $clientOrder->setState($stateRepository->findOneBy(['stateName' => 'Commandé']));

            $clientOrder->setClient($this->getUser());
            
            $clientOrder->setAdresse($this->getUser()->getAdresse());

            foreach ($panierWithDAta as $item){

                $logLine = new OrderLog();

                $logLine->setProduct($item['product']);
                $logLine->setQuantity($item['quantity']);
                $logLine->setBuyingPrice(($item['product']->getPriceHt())*($item['product']->getTva()));
                $logLine->setOrderNumber($clientOrder);
                $clientOrder->addOrderLog($logLine);

                $entityManager->persist($logLine);
            }
            $entityManager->persist($clientOrder);
            
            $entityManager->flush();

// Emptying the cart if order is made :
            $session->set('panier', []);

// Creation of an order summary to display on the order_completed page, I request the db to get all items related to the client order :
            $orderSummary = $orderLogRepository->findBy(['orderNumber' => $clientOrder->getId()]);

            return $this->render('order/order_completed.html.twig', [
                'summary' => $orderSummary,
                'total' => $total
            ]);
        }
        // If the user doesn't has an account :        
    } else {

// I'll redirect him toward the login page but first I save a boolean in order to redirect the user back to the order page after y logged in, 
// ( and not redirect him toward the homepage) :    
        $session = $requestStack->getSession();
        $session->set('hasCart', true);

        return $this->redirectToRoute('app_login');
    }
    
    return $this->render('order/validation.html.twig', [
        'orderForm' => $orderForm->createView(),
        'items' => $panierWithDAta,
        'total' => $total,
        'address' => $this->getUser()->getAdresse()
    ]);
    }
}
