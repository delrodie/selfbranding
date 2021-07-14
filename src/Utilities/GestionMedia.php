<?php


namespace App\Utilities;


use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GestionMedia
{
    private $mediaPresentation;
    private $mediaService;
    private $mediaEncyclo1;
    private $mediaEncyclo2;
    private $mediaBlog;

    public function __construct($presentationDirectory, $serviceDirectory, $encyclo1Directory, $encyclo2Directory, $blogDirectory)
    {
        $this->mediaPresentation = $presentationDirectory;
        $this->mediaService = $serviceDirectory;
        $this->mediaEncyclo1 = $encyclo1Directory;
        $this->mediaEncyclo2 = $encyclo2Directory;
        $this->mediaBlog = $blogDirectory;
    }

    /**
     * Enregistrement du fichier dans le repertoire approprié
     *
     * @param UploadedFile $file
     * @param null $media
     * @return string
     */
    public function upload(UploadedFile $file, $media = null)
    {
        // Initialisation du slug
        $slugify = new Slugify(); //dd($file);

        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugify->slugify($originalFileName);
        $newFilename = $safeFilename.'-'.Time().'.'.$file->guessExtension(); //dd($this->mediaActivite);

        // Deplacement du fichier dans le repertoire dedié
        try {
            if ($media === 'presentation') $file->move($this->mediaPresentation, $newFilename);
            elseif ($media === 'service') $file->move($this->mediaService, $newFilename);
            elseif ($media === 'encyclo1') $file->move($this->mediaEncyclo1, $newFilename);
            elseif ($media === 'encyclo2') $file->move($this->mediaEncyclo2, $newFilename);
            elseif ($media === 'blog') $file->move($this->mediaBlog, $newFilename);
            else $file->move($this->mediaPresentation, $newFilename);
        }catch (FileException $e){

        }

        return $newFilename;
    }

    /**
     * Suppression de l'ancien media sur le server
     *
     * @param $ancienMedia
     * @param null $media
     * @return bool
     */
    public function removeUpload($ancienMedia, $media = null)
    {
        if ($media === 'presentation') unlink($this->mediaPresentation.'/'.$ancienMedia);
        elseif ($media === 'encyclo1') unlink($this->mediaEncyclo1.'/'.$ancienMedia);
        elseif ($media === 'encyclo2') unlink($this->mediaEncyclo2.'/'.$ancienMedia);
        elseif ($media === 'service') unlink($this->mediaService.'/'.$ancienMedia);
        elseif ($media === 'blog') unlink($this->mediaBlog.'/'.$ancienMedia);
        else return false;

        return true;
    }
}