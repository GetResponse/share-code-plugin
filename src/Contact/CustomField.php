<?php
namespace GrShareCode\Contact;

/**
 * Class CustomField
 * @package GrShareCode\Contact
 */
class CustomField
{
    /** @var string */
    private $id;

    /** @var string */
    private $value;

    /**
     * @param string $id
     * @param string $value
     */
    public function __construct($id, $value)
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
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
