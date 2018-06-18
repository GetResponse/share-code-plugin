<?php
namespace GrShareCode\ContactList;

use GrShareCode\TypedCollection;

/**
 * Class CampaignsCollection
 * @package GrShareCode\ContactList
 */
class CampaignsCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\ContactList\Campaign');
    }
}
