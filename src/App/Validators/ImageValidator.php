<?php
namespace Paw\App\Validators;

use Paw\Core\Exceptions\InvalidImageException;

class ImageValidator
{
    static public function validateImage($file)
    {
        // Validar el tamaño de la imagen
        $maxSize = 1 * 1024 * 1024; // 1MB
        if ($file['size'] > $maxSize) {
            throw new InvalidImageException('El tamaño de la imagen excede el límite permitido. Solo se permite hasta 1 MB'); 
        }

        // Validar que la extension del archivo sea una imagen
        $allowedFormats = ['image/png', 'image/jpeg'];        
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $file['tmp_name']);
        finfo_close($fileInfo);
        
        if (!in_array($mimeType, $allowedFormats)) {
            throw new InvalidImageException('El archivo subido no tiene un formato de imagen aceptado. Solo se permite PNG Y JPEG.');
        }

        // Validar el número mágico de la imagen
        $magicNumbers = [
            'png' => "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A",
            'jpeg' => "\xFF\xD8\xFF",
            'jpg' => "\xFF\xD8\xFF",
        ];

        $fileHandle = fopen($file['tmp_name'], 'rb');
        $magicNumber = fread($fileHandle, 8);
        fclose($fileHandle);

        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        if ($magicNumbers[$fileExtension] !== $magicNumber) {
            throw new InvalidImageException('El archivo subido no tiene un formato de imagen aceptado. Solo se permite PNG Y JPEG.');
        }

        // Si todas las validaciones pasan, la imagen es válida
        return true;
    }
}
