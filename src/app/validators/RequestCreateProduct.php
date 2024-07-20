<?php

namespace Paw\App\Validators;

use Paw\Core\Request;
use Paw\Core\Exceptions\InvalidValueFormatException;

class RequestCreateProduct
{
    public static function validate(Request $request, array $requiredParams)
    {
        $postParams = $request->post();

        $missingParams = [];
        foreach ($requiredParams as $param) {
            if (!array_key_exists($param, $postParams)) {
                $missingParams[] = $param;
            }
        }


        if (!empty($missingParams)) {
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
