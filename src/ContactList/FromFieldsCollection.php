<?php
namespace GrShareCode\ContactList;

use GrShareCode\TypedCollection;

/**
 * Class FromFieldsCollection
 * @package GrShareCode\ContactList
 */
class FromFieldsCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\ContactList\FromFields');
    }
}