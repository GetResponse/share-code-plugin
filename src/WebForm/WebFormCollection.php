<?php
namespace GrShareCode\WebForm;

use GrShareCode\TypedCollection;

/**
 * Class WebFormCollection
 * @package GrShareCode\WebForm
 */
class WebFormCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\WebForm\WebForm');
    }
}