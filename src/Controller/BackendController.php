<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend")
 */
class BackendController extends AbstractController
{
    const menu = "invitant";
    const sub_menu = "invitant";

    /**
     * @Route("/", name="backend_dashbord")
     */
    public function index(): Response
    {
        return $this->render('backend/index.html.twig', [
            'controller_name' => 'BackendController',
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }
}
