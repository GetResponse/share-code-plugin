<?php
namespace GrShareCode\CustomField\CustomFieldFilter;

use GrShareCode\CustomField\CustomField;
use GrShareCode\Matcher;

/**
 * Class CustomFieldForMappingFilter
 * @package GrShareCode\CustomField\CustomFieldFilter
 */
class CustomFieldForMappingFilter implements Matcher
{
    const NAME_ORIGIN = 'origin';

    /**
     * @param CustomField $item
     * @return bool
     */
    public function matches($item)
    {
        return $item->getName() !== self::NAME_ORIGIN && $item->isTextField();
    }

}