<?php
namespace GrShareCode\Account;

/**
 * Class Account
 * @package GrShareCode\Account
 */
class Account
{
    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var string */
    private $email;

    /** @var string */
    private $companyName;

    /** @var string */
    private $phone;

    /** @var string */
    private $city;

    /** @var string */
    private $street;

    /** @var string */
    private $zipCode;

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $companyName
     * @param string $phone
     * @param string $city
     * @param string $street
     * @param string $zipCode
     */
    public function __construct($firstName, $lastName, $email, $companyName, $phone, $city, $street, $zipCode)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->companyName = $companyName;
        $this->phone = $phone;
        $this->city = $city;
        $this->street = $street;
        $this->zipCode = $zipCode;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getFullAddress()
    {
        return $this->getCity() . ' ' . $this->getStreet() . ' ' . $this->getZipCode();
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

}