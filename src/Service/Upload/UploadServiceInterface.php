<?php

namespace App\Service\Upload;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploadServiceInterface
{
    public function upload(UploadedFile $file, String $path): string;

    public function remove(string $path, string $filename): void;
}
