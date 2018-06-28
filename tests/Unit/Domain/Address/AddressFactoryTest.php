<?php
namespace GrShareCode\Tests\Unit\Domain\Address;
use GrShareCode\Address\AddressFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class AddressFactoryTest
 * @package GrShareCode\Tests\Unit\Domain\Address
 */
class AddressFactoryTest extends TestCase
{
    /**
     * Problem polega na tym, że substring na pustym stringu '' zwraca FALSE
     * i walidujemy pole które jest wartością FALSE zamiast string
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
    }
}