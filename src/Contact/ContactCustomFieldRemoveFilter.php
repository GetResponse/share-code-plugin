<?php

namespace GrShareCode\Contact;

use GrShareCode\Matcher;

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