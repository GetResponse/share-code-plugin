<?php
namespace GrShareCode\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Class BaseTestCase
 * @package GrShareCode\Tests\Unit
 */
class BaseTestCase extends TestCase
{
    /**
     * @param string $name
     * @param string[] $methodsToOverride
     * @return \PHPUnit_Framework_MockObject_MockObject | object
     */
    protected function getMockWithoutConstructing($name, array $methodsToOverride = [])
    {
        return $this->getMockBuilder($name)
            ->disableOriginalConstructor()
            ->setMethods($methodsToOverride)
            ->getMock();
    }
}