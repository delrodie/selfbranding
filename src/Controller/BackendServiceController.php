<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use App\Utilities\GestionMedia;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/service")
 */
class BackendServiceController extends AbstractController
{
    const menu = "gestion";
    const sub_menu = "service";

    private $gestionMedia;

    public function __construct(GestionMedia $gestionMedia)
    {
        $this->gestionMedia = $gestionMedia;
    }

    /**
     * @Route("/", name="backend_service_index", methods={"GET"})
     */
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('backend_service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="backend_service_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($service->getTitre());
            $service->setSlug($slug);

            $mediaFile = $form->get('media')->getData();
            $this->uploadMedia($service, $mediaFile);

            $entityManager->persist($service);
            $entityManager->flush();

            $this->addFlash('success', "Le service ".$service->getTitre()." a été ajouté avec succès!");

            return $this->redirectToRoute('backend_service_index');
        }

        return $this->render('backend_service/new.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_service_show", methods={"GET"})
     */
    public function show(Service $service): Response
    {
        return $this->render('backend_service/show.html.twig', [
            'service' => $service,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_service_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Service $service): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($service->getTitre());
            $service->setSlug($slug);

            $mediaFile = $form->get('media')->getData();
            $this->uploadMedia($service, $mediaFile);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Le service ".$service->getTitre()." a été modifié avec succès!");

            return $this->redirectToRoute('backend_service_index');
        }

        return $this->render('backend_service/edit.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_service_delete", methods={"POST"})
     */
    public function delete(Request $request, Service $service): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $media = $service->getMedia();
            $entityManager->remove($service);
            $entityManager->flush();

            $this->removeMedia($media);

            $this->addFlash('success', "Service supprimé avec succès!");

        }

        return $this->redirectToRoute('backend_service_index');
    }

    protected function uploadMedia($service, $mediaFile)
    {
        if ($mediaFile){
            $media = $this->gestionMedia->upload($mediaFile, 'service'); //dd($activite->getLogo());

            // Supression de l'ancien fichier
            $this->removeMedia($service->getMedia());

            $service->setMedia($media);
        }

        return;
    }

    protected function removeMedia($media)
    {
        if ($media) $this->gestionMedia->removeUpload($media, 'service');

        return;
    }
}
