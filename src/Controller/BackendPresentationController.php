<?php

namespace App\Controller;

use App\Entity\Presentation;
use App\Form\PresentationType;
use App\Repository\PresentationRepository;
use App\Utilities\GestionMedia;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/presentation")
 */
class BackendPresentationController extends AbstractController
{
    const menu = "gestion";
    const sub_menu = "presentation";

    private $gestionMedia;

    public function __construct(GestionMedia $gestionMedia)
    {
        $this->gestionMedia = $gestionMedia;
    }

    /**
     * @Route("/", name="backend_presentation_index", methods={"GET"})
     */
    public function index(PresentationRepository $presentationRepository): Response
    {
        return $this->render('backend_presentation/index.html.twig', [
            'presentations' => $presentationRepository->findAll(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="backend_presentation_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $presentation = new Presentation();
        $form = $this->createForm(PresentationType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($presentation->getTitre());
            $presentation->setSlug($slug);

            $mediaFile1 = $form->get('media1')->getData(); //dd($mediaFile);
            $mediaFile2 = $form->get('media2')->getData(); //dd($mediaFile);

            if ($mediaFile1){
                $media = $this->gestionMedia->upload($mediaFile1, 'presentation'); //dd($activite->getLogo());

                // Supression de l'ancien fichier
                //$this->gestionMedia->removeUpload($activite->getLogo(), 'activite');

                $presentation->setMedia1($media);
            }

            if ($mediaFile2){
                $media2 = $this->gestionMedia->upload($mediaFile2, 'presentation'); //dd($activite->getLogo());

                // Supression de l'ancien fichier
                //$this->gestionMedia->removeUpload($activite->getLogo(), 'activite');

                $presentation->setMedia2($media2);
            }
            $presentation->setCreatedBy($this->getUser()->getUserIdentifier());

            $entityManager->persist($presentation);
            $entityManager->flush();

            $this->addFlash('success', "La présentation a été ajouté avec succès.");

            return $this->redirectToRoute('backend_presentation_index');
        }

        return $this->render('backend_presentation/new.html.twig', [
            'presentation' => $presentation,
            'form' => $form->createView(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_presentation_show", methods={"GET"})
     */
    public function show(Presentation $presentation): Response
    {
        return $this->render('backend_presentation/show.html.twig', [
            'presentation' => $presentation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_presentation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Presentation $presentation): Response
    {
        $form = $this->createForm(PresentationType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($presentation->getTitre());
            $presentation->setSlug($slug);

            $mediaFile1 = $form->get('media1')->getData(); //dd($mediaFile);
            $mediaFile2 = $form->get('media2')->getData(); //dd($mediaFile);

            if ($mediaFile1){
                $media = $this->gestionMedia->upload($mediaFile1, 'presentation'); //dd($activite->getLogo());

                // Supression de l'ancien fichier
                $this->gestionMedia->removeUpload($presentation->getMedia1(), 'presentation');

                $presentation->setMedia1($media);
            }

            if ($mediaFile2){
                $media2 = $this->gestionMedia->upload($mediaFile2, 'presentation'); //dd($activite->getLogo());

                // Supression de l'ancien fichier
                $this->gestionMedia->removeUpload($presentation->getMedia2(), 'presentation');

                $presentation->setMedia2($media2);
            }
            $presentation->setUpdatedBy($this->getUser()->getUserIdentifier());

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "La presentation a été modifiée avec succès!");

            return $this->redirectToRoute('backend_presentation_index');
        }

        return $this->render('backend_presentation/edit.html.twig', [
            'presentation' => $presentation,
            'form' => $form->createView(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_presentation_delete", methods={"POST"})
     */
    public function delete(Request $request, Presentation $presentation): Response
    {
        if ($this->isCsrfTokenValid('delete'.$presentation->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $medias = ['media1'=>$presentation->getMedia1(), 'media2'=>$presentation->getMedia2()];
            $entityManager->remove($presentation);
            $entityManager->flush();

            // Supression de l'ancien fichier
            $this->gestionMedia->removeUpload($medias['media1'], 'presentation');
            $this->gestionMedia->removeUpload($medias['media2'], 'presentation');

            $this->addFlash('success', "La présentation a été supprimée avec succès!");

        }

        return $this->redirectToRoute('backend_presentation_index');
    }
}
