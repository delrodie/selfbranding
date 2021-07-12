<?php

namespace App\Controller;

use App\Entity\Encyclopreneur;
use App\Entity\Presentation;
use App\Entity\Service;
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
        $service = $this->getDoctrine()->getRepository(Service::class)->findByTag('developpement',true); //dd($service);
        return $this->render('home/index.html.twig', [
            'presentation' => $this->getDoctrine()->getRepository(Presentation::class)->findOneBy([],['id'=>"DESC"]),
            'developments' => $this->getDoctrine()->getRepository(Service::class)->findByTag('developpement'),
            'suivis' => $this->getDoctrine()->getRepository(Service::class)->findByTag('developpement',true),
            'encyclopreneurs' => $this->getDoctrine()->getRepository(Encyclopreneur::class)->findBy([],['id'=>"DESC"], 6)
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
