<?php
namespace GrShareCode\Address;

use GrShareCode\CountryCodeConverter;

/**
 * Class AddressFactory
 * @package GrShareCode\Address
 */
class AddressFactory
{
    /**
     * @param string $countryCode
     * @param string $firstName
     * @param string $lastName
     * @param string $address1
     * @param string $address2
     * @param string $city
     * @param string $zip
     * @param string $province
     * @param string $provinceCode
     * @param string $phone
     * @param string $company
     *
     * @return Address
     */
    public static function createFromParams(
        $countryCode,
        $firstName,
        $lastName,
        $address1,
        $address2,
        $city,
        $zip,
        $province,
        $provinceCode,
        $phone,
        $company
    )
    {
        $address = new Address(
            CountryCodeConverter::getCountryCodeInISO3166Alpha3($countryCode),
            $firstName.' '.$lastName
        );

        $address->setFirstName(substr($firstName, 0, 64));
        $address->setLastName(substr($lastName, 0, 64));
        $address->setAddress1(substr($address1, 0, 255));
        $address->setAddress2(substr($address2, 0, 255));
        $address->setCity(substr($city, 0, 128));
        $address->setZip(substr($zip, 0, 64));
        $address->setProvince(substr($province, 0, 255));
        $address->setProvinceCode(substr($provinceCode, 0, 64));
        $address->setPhone(substr($phone, 0, 255));
        $address->setCompany(substr($company, 0, 128));

        return $address;
    }
}
