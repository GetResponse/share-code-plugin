<?php

/**
 * Assert
 *
 * LICENSE
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@beberlei.de so I can send you a copy immediately.
 */

namespace GrShareCode\Validation\Assert;

/**
 * Start validation on a value, returns {@link AssertionChain}.
 *
 * The invocation of this method starts an assertion chain
 * that is happening on the passed value.
 *
 * @example
 *
 *  \GrShareCode\Validation\Assert\that($value)->notEmpty()->integer();
 *  \GrShareCode\Validation\Assert\that($value)->nullOr()->string()->startsWith("Foo");
 *
 * The assertion chain can be stateful, that means be careful when you reuse
 * it. You should never pass around the chain.
 *
 * @param mixed  $value
 * @param string $defaultMessage
 * @param string $defaultPropertyPath
 *
 * @return \GrShareCode\Validation\Assert\AssertionChain
 */
function that($value, $defaultMessage = null, $defaultPropertyPath = null)
{
    return Assert::that($value, $defaultMessage, $defaultPropertyPath);
}

/**
 * Start validation on a set of values, returns {@link AssertionChain}.
 *
 * @param mixed  $values
 * @param string $defaultMessage
 * @param string $defaultPropertyPath
 *
 * @return \GrShareCode\Validation\Assert\AssertionChain
 */
function thatAll($values, $defaultMessage = null, $defaultPropertyPath = null)
{
    return Assert::thatAll($values, $defaultMessage, $defaultPropertyPath);
}

/**
 * Start validation and allow NULL, returns {@link AssertionChain}.
 *
 * @param mixed  $value
 * @param string $defaultMessage
 * @param string $defaultPropertyPath
 *
 * @return \GrShareCode\Validation\Assert\AssertionChain
 *
 * @deprecated In favour of Assert::thatNullOr($value, $defaultMessage = null, $defaultPropertyPath = null)
 */
function thatNullOr($value, $defaultMessage = null, $defaultPropertyPath = null)
{
    return Assert::thatNullOr($value, $defaultMessage, $defaultPropertyPath);
}

/**
 * Create a lazy assertion object.
 *
 * @return \GrShareCode\Validation\Assert\LazyAssertion
 */
function lazy()
{
    return Assert::lazy();
}
