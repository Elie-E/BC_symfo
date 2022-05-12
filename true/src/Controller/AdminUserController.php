<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/api/current-user", name="admin_user", methods={"GET", "HEAD"})
     */
    public function currentUser(): Response
    {
        return $this->json($this->getUser());
    }
}