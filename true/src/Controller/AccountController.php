<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Client;
use App\Entity\Order;
use App\Form\AdresseType;
use App\Form\ClientType;
use App\Repository\AdresseRepository;
use App\Repository\ClientRepository;
use App\Repository\OrderLogRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    // Exemple sans le sensio-framework-extrabundle  :

    // /**
    //  * @Route("/account/{id}", name="account")
    //  */
    // public function showAccount($id): Response
    // {
    //     $account = $this->getDoctrine()->getRepository(Client::class)->find($id);

    //     return $this->render('account/account.html.twig', [
    //         'account' => $account
    //     ]);
    // }

// Avec sensioframework-extrabundle  :  

    /**
     * @Route("/account/{client}", name="account")
     */
    public function showAccount(Client $client, OrderRepository $orderRepository, OrderLogRepository $orderLogRepository)
    {   
        $orders = $orderRepository->findBy(["client"=>$client]);

        return $this->render('account/account.html.twig', [
            'account' => $client,
            'orders' => $orders
        ]);
    }
}
