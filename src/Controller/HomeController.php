<?php

namespace App\Controller;

use App\Entity\Presentation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'presentation' => $this->getDoctrine()->getRepository(Presentation::class)->findOneBy([],['id'=>"DESC"]),
        ]);
    }

    /**
     * @Route("/presentation", name="app_home_presentation")
     */
    public function presentation(): Response
    {
        return $this->render('home/index.html.twig', [
            'presentation' => $this->getDoctrine()->getRepository(Presentation::class)->findOneBy([],['id'=>"DESC"]),
        ]);
    }
}
