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
    ) {
        $address = new Address(
            CountryCodeConverter::getCountryCodeInISO3166Alpha3($countryCode),
            $firstName . ' ' . $lastName
        );

        $address
            ->setFirstName(substr($firstName, 0, Address::FIRSTNAME_MAX_LENGTH))
            ->setLastName(substr($lastName, 0, Address::LASTNAME_MAX_LENGTH))
            ->setAddress1(substr($address1, 0, Address::ADDRESS1_MAX_LENGTH))
            ->setAddress2(substr($address2, 0, Address::ADDRESS2_MAX_LENGTH))
            ->setCity(substr($city, 0, Address::CITY_MAX_LENGTH))
            ->setZip(substr($zip, 0, Address::ZIP_MAX_LENGTH))
            ->setProvince(substr($province, 0, Address::PROVINCE_MAX_LENGTH))
            ->setProvinceCode(substr($provinceCode, 0, Address::PROVINCE_CODE_MAX_LENGTH))
            ->setPhone(substr($phone, 0, Address::PHONE_MAX_LENGTH))
            ->setCompany(substr($company, 0, Address::COMPANY_MAX_LENGTH));

        return $address;
    }
}
