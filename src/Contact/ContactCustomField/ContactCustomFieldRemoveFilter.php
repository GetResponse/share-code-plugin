<?php

namespace GrShareCode\Contact\ContactCustomField;

use GrShareCode\Matcher;

/**
 * Class ContactCustomFieldRemoveFilter
 * @package GrShareCode\Contact\ContactCustomField
 */
class ContactCustomFieldRemoveFilter implements Matcher
{
    /** @var ContactCustomField */
    private $toRemove;

    /**
     * @param ContactCustomField $toRemove
     */
    public function __construct(ContactCustomField $toRemove)
    {
        $this->toRemove = $toRemove;
    }

    /**
     * @param ContactCustomField $item
     * @return bool
     */
    public function matches($item)
    {
        return $item->getId() !== $this->toRemove->getId();
    }
}