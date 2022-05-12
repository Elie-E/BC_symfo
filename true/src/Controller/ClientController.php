<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Client;
use App\Form\AdresseType;
use App\Form\ClientType;
use App\Repository\AdresseRepository;
use App\Repository\ClientRepository;
use phpDocumentor\Reflection\Location;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * @Route("/client")
 */
class ClientController extends AbstractController
{
    /**
     * @Route("/", name="client_index", methods={"GET"})
     */

    public function index(ClientRepository $clientRepository): Response
    {
            return $this->render('client/index.html.twig', [
                'clients' => $clientRepository->findAll(),
            ]);
    }

    /**
     * @Route("/new", name="client_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        $adresse = new Adresse();
        $adresseForm = $this->createForm(AdresseType::class, $adresse);
        $adresseForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            
            if($adresse->getCode() && $adresse->getLocation()) {
                
                $client->setAdresse($adresse);
                $entityManager->persist($adresse);
            }
            $entityManager->persist($client);

            $entityManager->flush();

            return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/new.html.twig', [
            'client' => $client,
            'form' => $form,

            'adresseForm' => $adresseForm
        ]);
    }

    /**
     * @Route("/{id}", name="client_show", methods={"GET"})
     */
    public function show(Client $client, AdresseRepository $adresseRepository): Response
    {

            $adresse = $adresseRepository->find($client->getAdresse()->getId());

        return $this->render('client/show.html.twig', [
            'client' => $client,
            'adresse' => $adresse
        ]);
    }

    /**
     * @Route("/{id}/edit", name="client_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Client $client, AdresseRepository $adresseRepository): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if($client->getAdresse()){
            $id_adresse = $client->getAdresse()->getId();
            $adresse = $adresseRepository->find($id_adresse);
        } else {
            $adresse = new Adresse();
        }

        $adresseForm = $this->createForm(AdresseType::class, $adresse);
        $adresseForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
            'adresse' => $adresse,
            'adresseForm' => $adresseForm
        ]);
    }

    /**
     * @Route("/{id}", name="client_delete", methods={"POST"})
     */
    public function delete(Request $request, Client $client, AdresseRepository $adresseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->request->get('_token'))) {

            
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->remove($client);

            if($client->getAdresse()){  
                             
                $adresse = $adresseRepository->find($client->getAdresse()->getId());
                $entityManager->remove($adresse);
            }

            $entityManager->flush();
        }

        return $this->redirectToRoute('client_index', [], Response::HTTP_SEE_OTHER);
    }
}
