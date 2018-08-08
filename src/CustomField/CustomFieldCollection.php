<?php
namespace GrShareCode\CustomField;

use GrShareCode\TypedCollection;

/**
 * Class CustomFieldCollection
 * @package GrShareCode\CustomField
 */
class CustomFieldCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\CustomField\CustomField');
    }
}
