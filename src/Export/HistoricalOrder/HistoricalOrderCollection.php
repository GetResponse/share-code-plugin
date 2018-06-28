<?php
namespace GrShareCode\Export\HistoricalOrder;

use GrShareCode\TypedCollection;

/**
 * Class HistoricalOrderCollection
 * @package GrShareCode\Export\HistoricalOrder
 */
class HistoricalOrderCollection extends TypedCollection
{
    public function __construct()
    {
        $this->setItemType('\GrShareCode\Export\HistoricalOrder\HistoricalOrder');
    }
}