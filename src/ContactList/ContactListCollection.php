<?php
namespace GrShareCode\ContactList;

use GrShareCode\TypedCollection;

/**
 * Class ContactListCollection
 * @package GrShareCode\ContactList
 */
class ContactListCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\ContactList\ContactList');
    }
}
