<?php
namespace GrShareCode\Cart\Validation;

use GrShareCode\Validation\Assert\Assertion as BaseAssertion;

/**
 * Class Assertion
 * @package GrShareCode\Cart
 */
class Assertion extends BaseAssertion
{
    protected static $exceptionClass = '\GrShareCode\Cart\Validation\AddCartCommandException';
}