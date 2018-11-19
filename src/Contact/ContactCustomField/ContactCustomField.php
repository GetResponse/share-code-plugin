<?php
namespace GrShareCode\Contact\ContactCustomField;

/**
 * Class ContactCustomField
 * @package GrShareCode\Contact\ContactCustomField
 */
class ContactCustomField
{
    /** @var string */
    private $id;

    /** @var array */
    private $values;

    /**
     * @param string $id
     * @param array $values
     */
    public function __construct($id, array $values)
    {
        $this->id = $id;
        $this->values = $values;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

}
