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
            'encyclopreneurs' => $this->getDoctrine()->getRepository(Encyclopreneur::class)->findBy([],['id'=>"DESC"]),
            'dernier' => $this->getDoctrine()->getRepository(Encyclopreneur::class)->findOneBy([],['updatedAt'=>'DESC']),
            'premier' => $this->getDoctrine()->getRepository(Encyclopreneur::class)->findOneBy([],['createdAt'=>'ASC']),
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
