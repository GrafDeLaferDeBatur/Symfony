<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\File;
class JSONUploader
{

    public function __construct(
        private readonly string           $targetDirectory,
        private readonly SluggerInterface $slugger,
        private readonly Filesystem       $_filesystem,
    ) {
        $this->filesystem = $this->_filesystem;
    }

    public function upload(UploadedFile $file): string
    {

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
        }
        return $fileName;
    }


    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
