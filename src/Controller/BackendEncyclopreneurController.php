<?php

namespace App\Controller;

use App\Entity\Encyclopreneur;
use App\Form\EncyclopreneurType;
use App\Repository\EncyclopreneurRepository;
use App\Utilities\GestionMedia;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/encyclopreneur")
 */
class BackendEncyclopreneurController extends AbstractController
{
    const menu = "gestion";
    const sub_menu = "encyclopreneur";

    private $gestionMedia;

    public function __construct(GestionMedia $gestionMedia)
    {
        $this->gestionMedia = $gestionMedia;
    }

    /**
     * @Route("/", name="backend_encyclopreneur_index", methods={"GET"})
     */
    public function index(EncyclopreneurRepository $encyclopreneurRepository): Response
    {
        return $this->render('backend_encyclopreneur/index.html.twig', [
            'encyclopreneurs' => $encyclopreneurRepository->findAll(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="backend_encyclopreneur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $encyclopreneur = new Encyclopreneur();
        $form = $this->createForm(EncyclopreneurType::class, $encyclopreneur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($encyclopreneur->getNom().'-'.$encyclopreneur->getPrenoms());
            $encyclopreneur->setSlug($slug);

            $mediaFile1 = $form->get('media1')->getData(); //dd($mediaFile);
            $mediaFile2 = $form->get('media2')->getData(); //dd($mediaFile);

            if ($mediaFile1){
                $media = $this->gestionMedia->upload($mediaFile1, 'encyclo1'); //dd($activite->getLogo());

                // Supression de l'ancien fichier
                //$this->gestionMedia->removeUpload($encyclopreneur->getMedia1(), 'encyclo1');

                $encyclopreneur->setMedia1($media);
            }

            if ($mediaFile2){
                $media2 = $this->gestionMedia->upload($mediaFile2, 'encyclo2'); //dd($activite->getLogo());

                // Supression de l'ancien fichier
                //$this->gestionMedia->removeUpload($encyclopreneur->getMedia2(), 'encyclo2');

                $encyclopreneur->setMedia2($media2);
            }
            $encyclopreneur->setCreatedBy($this->getUser()->getUserIdentifier());

            $entityManager->persist($encyclopreneur);
            $entityManager->flush();

            $this->addFlash('success', $encyclopreneur->getNom().' '.$encyclopreneur->getPrenoms()." a bien été ajouté à la liste des entrepreneurs");

            return $this->redirectToRoute('backend_encyclopreneur_index');
        }

        return $this->render('backend_encyclopreneur/new.html.twig', [
            'encyclopreneur' => $encyclopreneur,
            'form' => $form->createView(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_encyclopreneur_show", methods={"GET"})
     */
    public function show(Encyclopreneur $encyclopreneur): Response
    {
        return $this->render('backend_encyclopreneur/show.html.twig', [
            'encyclopreneur' => $encyclopreneur,
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_encyclopreneur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Encyclopreneur $encyclopreneur): Response
    {
        $form = $this->createForm(EncyclopreneurType::class, $encyclopreneur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($encyclopreneur->getNom().'-'.$encyclopreneur->getPrenoms());
            $encyclopreneur->setSlug($slug);

            $mediaFile1 = $form->get('media1')->getData(); //dd($mediaFile);
            $mediaFile2 = $form->get('media2')->getData(); //dd($mediaFile);

            if ($mediaFile1){
                $media = $this->gestionMedia->upload($mediaFile1, 'encyclo1'); //dd($activite->getLogo());

                // Supression de l'ancien fichier
                $this->gestionMedia->removeUpload($encyclopreneur->getMedia1(), 'encyclo1');

                $encyclopreneur->setMedia1($media);
            }

            if ($mediaFile2){
                $media2 = $this->gestionMedia->upload($mediaFile2, 'encyclo2'); //dd($activite->getLogo());

                // Supression de l'ancien fichier
                $this->gestionMedia->removeUpload($encyclopreneur->getMedia2(), 'encyclo2');

                $encyclopreneur->setMedia2($media2);
            }
            $encyclopreneur->setUpdatedBy($this->getUser()->getUserIdentifier());

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "les informations de l'entrepreneur.e ".$encyclopreneur->getNom().' '.$encyclopreneur->getPrenoms()." ont bien été modifiées.");

            return $this->redirectToRoute('backend_encyclopreneur_index');
        }

        return $this->render('backend_encyclopreneur/edit.html.twig', [
            'encyclopreneur' => $encyclopreneur,
            'form' => $form->createView(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_encyclopreneur_delete", methods={"POST"})
     */
    public function delete(Request $request, Encyclopreneur $encyclopreneur): Response
    {
        $identite =$encyclopreneur->getNom().' '.$encyclopreneur->getPrenoms();

        if ($this->isCsrfTokenValid('delete'.$encyclopreneur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($encyclopreneur);
            $entityManager->flush();

            $this->addFlash('success', $identite." a bien été ajouté à la liste des entrepreneurs");

        }

        return $this->redirectToRoute('backend_encyclopreneur_index');
    }

}
