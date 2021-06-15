<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class FilenameGenerator
{
    /**
     * @var string
     */
    private $slugger;

    public function __construct(string $uploads_directory, SluggerInterface $slugger)
    {
        $this->uploads_directory = $uploads_directory;
        $this->slugger = $slugger;
    }

    /**
     * Check if the file extension is authorized.
     */
    public function checkPhotoExt($file)
    {
        if ($file->guessExtension() !== '.jpeg' &&
            $file->guessExtension() !== '.jpg' &&
            $file->guessExtension() !== '.png') {
            return false;
        }

        return true;
    }

    /**
     * Check if the file extension is authorized.
     */
    public function checkVideoExt($file)
    {
        if ($file->guessExtension() !== '.mp4' &&
            $file->guessExtension() !== '.ogv' &&
            $file->guessExtension() !== '.webm') {
            return false;
        }

        return true;
    }

    /**
     * Generate a new filename after randomizing it.
     */
    public function generate($file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                $this->uploads_directory,
                $newFilename
            );
        } catch (FileException $e) {
            throw new \Exception('Something went wrong!');
        }

        return $newFilename;
    }
}