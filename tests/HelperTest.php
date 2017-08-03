<?php

/**
 * HelperTest class
 * Contains unit tests for the helper functions
 * 
 * @package bingo-router
 * @license Apache 2.0
 * @author Lochemem Bruno Michael <lochbm@gmail.com>
 */

use PHPUnit\Framework\TestCase;
use function Chemem\Bingo\Router\Helpers\compose;
use function Chemem\Bingo\Router\Helpers\identity;

class HelperTest extends TestCase
{
    /**
     * Tests whether the identity function proves the identity law of functors
     */

    public function testIdentityFunctionProvesIdentityLaw()
    {
        $x = 9;
        $func = identity($x);
        $this->assertEquals($func, $x);
    }

    /**
     * Tests whether the compose function helps with function composition
     */

    public function testComposeFunctionCombinesFunctions()
    {
        $composed = compose(
            function (string $val) : string {
                return strtoupper($val);
            },
            function (string $val) : string {
                return substr($val, 0, 3);
            }
        );
        $newValues = array_map($composed, ['bingo', 'laravel', 'phalcon']);
        $this->assertEquals($newValues, ['BIN', 'LAR', 'PHA']);
    }
}
