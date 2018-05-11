<?php
namespace GrShareCode\Campaign;

use GrShareCode\TypedCollection;

/**
 * Class CampaignsCollection
 * @package GrShareCode\Campaign
 */
class CampaignsCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\Campaign\Campaign');
    }
}
