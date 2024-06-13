<?php

use Paw\App\Validators\ImageValidator;
use Paw\Core\Exceptions\InvalidImageException;

class ImageHandler
{
    private string $baseDir;

    public function __construct(string $baseDir)
    {
        $this->baseDir = $baseDir;
    }

    public function saveImage(array $img, string $subDir, array $sizes = ['S', 'M', 'L']): string
    {
        ImageValidator::validateImage($img);
        $dir = $this->baseDir . '/' . $subDir;
        $realDir = realpath($dir);

        if (!$realDir && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new InvalidImageException("Error al crear el directorio {$dir}");
        }

        $filename = uniqid();
        $absolutePath = $realDir . '/' . $filename;
        $ok = move_uploaded_file($img['tmp_name'], $absolutePath);

        if ($ok) {
            $this->generateImageCopies($absolutePath, $img, $sizes);
            return $filename;
        } else {
            throw new InvalidImageException("Error al subir la imagen");
        }
    }

    private function generateImageCopies(string $absolutePath, array $img, array $sizes): void
    {
        $extension = pathinfo($img['name'], PATHINFO_EXTENSION);

        foreach ($sizes as $size) {
            copy($absolutePath, "{$absolutePath}-{$size}.{$extension}");
        }
    }
}
