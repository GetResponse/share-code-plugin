<?php
namespace GrShareCode\Job;

use GrShareCode\TypedCollection;

/**
 * Class JobCollection
 * @package GrShareCode\Job
 */
class JobCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\Job\Job');
    }
}
