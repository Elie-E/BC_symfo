<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Client;
use App\Form\AdresseType;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new Client();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        $adresse = new Adresse();
        $adresseForm = $this->createForm(AdresseType::class, $adresse);
        $adresseForm->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if($adresse->getCode() && $adresse->getLocation()) {
                
                $user->setAdresse($adresse);
                $entityManager->persist($adresse);
            }

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles(["ROLE_USER"]);
            $entityManager->persist($user);

            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('home_page');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'adresseForm' => $adresseForm->createView()
        ]);
    }
}
