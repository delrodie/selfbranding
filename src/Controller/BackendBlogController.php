<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Utilities\GestionMedia;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/blog")
 */
class BackendBlogController extends AbstractController
{
    const menu = "blog";
    const sub_menu = "article";

    private $gestionMedia;

    public function __construct(GestionMedia $gestionMedia)
    {
        $this->gestionMedia = $gestionMedia;
    }

    /**
     * @Route("/", name="backend_blog_index", methods={"GET"})
     */
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('backend_blog/index.html.twig', [
            'blogs' => $blogRepository->findAll(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/new", name="backend_blog_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($blog->getTitre());
            $blog->setSlug($slug);

            $mediaFile = $form->get('media')->getData();
            $this->uploadMedia($blog, $mediaFile);

            $entityManager->persist($blog);
            $entityManager->flush();

            $this->addFlash('success', "L'article ".$blog->getTitre()." a été ajouté avec succès!");

            return $this->redirectToRoute('backend_blog_index');
        }

        return $this->render('backend_blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_blog_show", methods={"GET"})
     */
    public function show(Blog $blog): Response
    {
        return $this->render('backend_blog/show.html.twig', [
            'blog' => $blog,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_blog_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Blog $blog): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // slug
            $slugify = new Slugify();
            $slug = $slugify->slugify($blog->getTitre());
            $blog->setSlug($slug);

            $mediaFile = $form->get('media')->getData();
            $this->uploadMedia($blog, $mediaFile);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'article ".$blog->getTitre()." a été modifié avec succès!");

            return $this->redirectToRoute('backend_blog_index');
        }

        return $this->render('backend_blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
            'menu' => self::menu,
            'sub_menu' => self::sub_menu
        ]);
    }

    /**
     * @Route("/{id}", name="backend_blog_delete", methods={"POST"})
     */
    public function delete(Request $request, Blog $blog): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($blog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_blog_index');
    }

    protected function uploadMedia($blog, $mediaFile)
    {
        if ($mediaFile){
            $media = $this->gestionMedia->upload($mediaFile, 'blog'); //dd($activite->getLogo());

            // Supression de l'ancien fichier
            $this->removeMedia($blog->getMedia());

            $blog->setMedia($media);
        }

        return;
    }

    protected function removeMedia($media)
    {
        if ($media) $this->gestionMedia->removeUpload($media, 'blog');

        return;
    }
}
