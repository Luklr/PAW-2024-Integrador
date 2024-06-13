<?php

namespace Paw\App\Validators;

use Paw\Core\Request;
use Paw\Core\Exceptions\InvalidValueFormatException;

class RequestAltaPlatoValidator
{
    public static function validate(Request $request, array $requiredParams)
    {
        if (!$request->hasBodyParams($requiredParams)) {
            throw new InvalidValueFormatException("Complete todos los campos");
        }

        $img = $request->file("imagen");
        if ($img["error"] !== 0) {
            throw new InvalidValueFormatException("Error al subir la imagen");
        }

        if (!isset($img) || !is_uploaded_file($img['tmp_name'])) {
            throw new InvalidValueFormatException("La imagen no fue subida correctamente");
        }
    }
}
