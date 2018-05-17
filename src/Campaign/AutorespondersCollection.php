<?php
namespace GrShareCode\Campaign;

use GrShareCode\TypedCollection;

/**
 * Class AutorespondersCollection
 * @package GrShareCode\Campaign
 */
class AutorespondersCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\Campaign\Autoresponder');
    }
}
