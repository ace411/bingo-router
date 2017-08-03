<?php

/**
 * Identity helper function
 * Useful for a variety of operations premised on the identity law of functors and monads
 *
 * @package bingo-router
 * @license Apache 2.0
 * @author Lochemem Bruno Michael <lochbm@gmail.com>
 */

namespace Chemem\Bingo\Router\Helpers;

/**
 * identity function
 *
 * @param mixed $value
 * @return mixed $value
 */

function identity($value)
{
    return $value;
}
