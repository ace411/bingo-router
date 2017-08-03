<?php

/**
 * Compose helper function
 * Useful for combining multiple callable functions into one function
 *
 * @package bingo-router
 * @license Apache 2.0
 * @author Lochemem Bruno Michael <lochbm@gmail.com>
 */

namespace Chemem\Bingo\Router\Helpers;

/**
 * compose function
 *
 * @param callable $fn The functions which are, incrementally added
 * @return mixed $value 
 */

function compose(callable ...$fn)
{
    return array_reduce($fn, function ($carry, $f) {
        return function ($val) use (&$carry, &$f) {
            return $f($carry($val));
        };
    }, 'Chemem\Bingo\Router\Helpers\identity');
}
