<?php

namespace App\Service\Upload;

use Psr\Log\LoggerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService implements UploadServiceInterface
{
    private SluggerInterface $slugger;
    private LoggerInterface $logger;

    public function __construct(SluggerInterface $slugger, LoggerInterface $logger)
    {
        $this->slugger = $slugger;
        $this->logger = $logger;
    }

    public function upload(UploadedFile $file, string $path): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        // transfert l'image dans un dossier spécifié dans le controller
        try {
            $file->move($path, $newFilename);
        } catch (FileException $e) {
            $this->logger->error('error:', [
                'exception' => $e,
            ]);
        }

        return $newFilename;
    }

    public function remove(string $path, string $filename): void
    {
        unlink($path . $filename);
    }
}
