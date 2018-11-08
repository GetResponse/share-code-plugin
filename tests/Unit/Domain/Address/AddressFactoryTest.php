<?php
namespace GrShareCode\Tests\Unit\Domain\Address;
use GrShareCode\Address\Address;
use GrShareCode\Address\AddressFactory;
use GrShareCode\Tests\Unit\BaseTestCase;

/**
 * Class AddressFactoryTest
 * @package GrShareCode\Tests\Unit\Domain\Address
 */
class AddressFactoryTest extends BaseTestCase
{
    /**
     * @test
     */
    public function shouldCreateValidAddressInstance()
    {
        $address = AddressFactory::createFromParams(
          'pol',
          'Jan',
          'Nowak',
          'Krótka 33',
          '', // to pole może być opcjonalne
            'Gdańsk',
          '90-900',
          '', // to pole może być puste
            '', // to pole może być puste
            '', // to pole może być puste
            '' // to pole może być puste
        );

        self::assertInstanceOf(Address::class, $address);
    }
}