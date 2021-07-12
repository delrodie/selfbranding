<?php

namespace App\Controller;

use App\Entity\Encyclopreneur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/encyclopreneur")
 */
class EncyclopreneurController extends AbstractController
{
    /**
     * @Route("/", name="frontend_encyclopreneur_index")
     */
    public function index(): Response
    {
        return $this->render('encyclopreneur/index.html.twig', [
            'controller_name' => 'EncyclopreneurController',
        ]);
    }

    /**
     * @Route("/{slug}", name="frontend_encyclopreneur_show", methods={"GET"})
     */
    public function show(Encyclopreneur $encyclopreneur)
    {
        return $this->render('encyclopreneur/show.html.twig',[
            'encyclopreneur' => $encyclopreneur
        ]);
    }
}
