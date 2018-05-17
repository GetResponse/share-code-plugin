<?php
namespace GrShareCode\Address;

use GrShareCode\Validation\Assert\Assert;

/**
 * Class Address
 * @package GrShareCode\Address
 */
class Address
{
    const FIRSTNAME_MAX_LENGTH = 64;
    const LASTNAME_MAX_LENGTH = 64;
    const ADDRESS1_MAX_LENGTH = 255;
    const ADDRESS2_MAX_LENGTH = 255;
    const CITY_MAX_LENGTH = 128;
    const ZIP_MAX_LENGTH = 64;
    const PROVINCE_MAX_LENGTH = 255;
    const PROVINCE_CODE_MAX_LENGTH = 64;
    const PHONE_MAX_LENGTH = 255;
    const COMPANY_MAX_LENGTH = 128;

    /** @var string */
    private $countryCode;

    /** @var string */
    private $countryName;

    /** @var string */
    private $name;

    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var string */
    private $address1;

    /** @var string */
    private $address2;

    /** @var string */
    private $city;

    /** @var string */
    private $zip;

    /** @var string */
    private $province;

    /** @var string */
    private $provinceCode;

    /** @var string */
    private $phone;

    /** @var string */
    private $company;

    /**
     * @param string $countryCode
     * @param string $name
     */
    public function __construct($countryCode, $name)
    {
        $this->setCountryCode($countryCode);
        $this->setName($name);
    }

    /**
     * @param string $countryCode
     */
    private function setCountryCode($countryCode)
    {
        Assert::that($countryCode)->notBlank()->string()->length(3);
        $this->countryCode = $countryCode;
    }

    /**
     * @param string $name
     */
    private function setName($name)
    {
        Assert::that($name)->notBlank()->string();
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        Assert::that($firstName)->maxLength(self::FIRSTNAME_MAX_LENGTH);
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        Assert::that($lastName)->maxLength(self::LASTNAME_MAX_LENGTH);
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * @param string $address1
     * @return $this
     */
    public function setAddress1($address1)
    {
        Assert::that($address1)->maxLength(self::ADDRESS1_MAX_LENGTH);
        $this->address1 = $address1;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param string $address2
     * @return $this
     */
    public function setAddress2($address2)
    {
        Assert::that($address2)->maxLength(self::ADDRESS2_MAX_LENGTH);
        $this->address2 = $address2;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        Assert::that($city)->maxLength(self::CITY_MAX_LENGTH);
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param string $zip
     * @return $this
     */
    public function setZip($zip)
    {
        Assert::that($zip)->maxLength(self::ZIP_MAX_LENGTH);
        $this->zip = $zip;

        return $this;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param string $province
     * @return $this
     */
    public function setProvince($province)
    {
        Assert::that($province)->maxLength(self::PROVINCE_MAX_LENGTH);
        $this->province = $province;

        return $this;
    }

    /**
     * @return string
     */
    public function getProvinceCode()
    {
        return $this->provinceCode;
    }

    /**
     * @param string $provinceCode
     * @return $this
     */
    public function setProvinceCode($provinceCode)
    {
        Assert::that($provinceCode)->maxLength(self::PROVINCE_CODE_MAX_LENGTH);
        $this->provinceCode = $provinceCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        Assert::that($phone)->maxLength(self::PHONE_MAX_LENGTH);
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     * @return $this
     */
    public function setCompany($company)
    {
        Assert::that($company)->maxLength(self::COMPANY_MAX_LENGTH);
        $this->company = $company;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * @param string $countryName
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;
    }
}
