<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class FilenameGenerator
{
    /**
     * @var string
     */
    private string $photos_directory;
    private $slugger;

    public function __construct(string $photos_directory, SluggerInterface $slugger)
    {
        $this->photos_directory = $photos_directory;
        $this->slugger = $slugger;
    }

    public function generate($file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                $this->photos_directory,
                $newFilename
            );
        } catch (FileException $e) {
            //TODO : handle exception
        }

        return $newFilename;
    }
}