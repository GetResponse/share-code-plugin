<?php
namespace GrShareCode\Contact;

/**
 * Class ContactCustomField
 * @package GrShareCode\Contact
 */
class ContactCustomField
{
    /** @var string */
    private $id;

    /** @var array */
    private $value;

    /**
     * @param string $id
     * @param array $value
     */
    public function __construct($id, array $value)
    {
        $this->id = $id;
        $this->value = $value;
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
    public function getValue()
    {
        return $this->value;
    }

}
