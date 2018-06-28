<?php
namespace GrShareCode\ContactList;

use GrShareCode\TypedCollection;

/**
 * Class AutorespondersCollection
 * @package GrShareCode\ContactList
 */
class AutorespondersCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\ContactList\Autoresponder');
    }
}
