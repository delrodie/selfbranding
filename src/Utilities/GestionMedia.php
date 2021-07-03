<?php


namespace App\Utilities;


use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GestionMedia
{
    private $mediaPresentation;
    private $mediaService;

    public function __construct($presentationDirectory, $serviceDirectory)
    {
        $this->mediaPresentation = $presentationDirectory;
        $this->mediaService = $serviceDirectory;
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
        elseif ($media === 'service') unlink($this->mediaService.'/'.$ancienMedia);
        else return false;

        return true;
    }
}