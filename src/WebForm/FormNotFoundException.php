<?php
namespace GrShareCode\WebForm;

use GrShareCode\GrShareCodeException;

/**
 * Class FormNotFoundException
 * @package GrShareCode\WebForm
 */
class FormNotFoundException extends GrShareCodeException
{

    /**
     * @param string $id
     * @return FormNotFoundException
     */
    public static function createWithId($id)
    {
        return new self(sprintf('Form with id %s not found in getResponse.' , $id));
    }
}