<?php

namespace App\Controller;

use App\Entity\Thematique;
use App\Form\ThematiqueType;
use App\Repository\ThematiqueRepository;
use App\Utilities\GestionMedia;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/thematique")
 */
class BackendThematiqueController extends AbstractController
{
    const menu = "blog";
    const sub_menu = "thematique";

    private $gestionMedia;

    public function __construct(GestionMedia $gestionMedia)
    {
        $this->gestionMedia = $gestionMedia;
    }

    /**
     * @Route("/", name="backend_thematique_index", methods={"GET"})
     */
    public function index(ThematiqueRepository $thematiqueRepository): Response
    {
        return $this->render('backend_thematique/index.html.twig', [
            'thematiques' => $thematiqueRepository->findAll(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="backend_thematique_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $thematique = new Thematique();
        $form = $this->createForm(ThematiqueType::class, $thematique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($thematique->getTitre());
            $thematique->setSlug($slug);

            $entityManager->persist($thematique);
            $entityManager->flush();

            $this->addFlash('success', $thematique->getTitre()." a été ajoutée avec succès.");

            return $this->redirectToRoute('backend_thematique_index');
        }

        return $this->render('backend_thematique/new.html.twig', [
            'thematique' => $thematique,
            'form' => $form->createView(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_thematique_show", methods={"GET"})
     */
    public function show(Thematique $thematique): Response
    {
        return $this->render('backend_thematique/show.html.twig', [
            'thematique' => $thematique,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_thematique_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Thematique $thematique): Response
    {
        $form = $this->createForm(ThematiqueType::class, $thematique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($thematique->getTitre());
            $thematique->setSlug($slug);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', $thematique->getTitre()." a été modifiée avec succès.");

            return $this->redirectToRoute('backend_thematique_index');
        }

        return $this->render('backend_thematique/edit.html.twig', [
            'thematique' => $thematique,
            'form' => $form->createView(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_thematique_delete", methods={"POST"})
     */
    public function delete(Request $request, Thematique $thematique): Response
    {
        if ($this->isCsrfTokenValid('delete'.$thematique->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($thematique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_thematique_index');
    }
}
