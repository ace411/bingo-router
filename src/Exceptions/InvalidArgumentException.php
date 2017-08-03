<?php

/**
 * Bingo Router InvalidArgumentException class
 * Useful for throwing exceptions
 *
 * @package bingo-router
 * @license Apache 2.0
 * @author Lochemem Bruno Michael <lochbm@gmail.com>
 * @see InvalidArgumentException::class
 */

namespace Chemem\Bingo\Router\Exceptions;

use Chemem\Bingo\Router\Common\RouterExceptionInterface;

class InvalidArgumentException extends \InvalidArgumentException implements RouterExceptionInterface
{
    /**
     * invalidMatch method
     *
     * @param mixed $string The invalid string
     * @param string $expected The expected pattern
     * @param callable $method The method in which the exception is thrown
     * @throws InvalidArgumentException
     */

    public static function invalidMatch($string, $expected, $method)
    {
        throw new static("The string {$string} does not match the pattern {$expected} in {$method}");
    }

    /**
     * invalidArgument method
     *
     * @param mixed $string The invalid argument
     * @param string $expected The expected argument
     * @param callable $method The method in which the exception is thrown
     * @throws InvalidArgumentException
     */

    public static function invalidArgument($argument, $expected, $method)
    {
        $expType = gettype($expected); //expected argument type
        $argType = gettype($argument); //argument type
        throw new static("Argument {$argument} of type {$argType} does not match {$expType} in {$method}");
    }

    /**
     * invalidFunction method
     *
     * @param callable $function The invalid function
     * @param callable $method The method in which the exception is thrown
     * @throws InvalidArgumentException
     */

    public static function invalidFunction($function, $method)
    {
        $reflectionFunction = new \ReflectionFunction($function);
        $function = is_callable($function) ? $reflectionFunction->getParameters() : gettype($function);
        $msg = !is_string($function) || !is_bool($function) || !is_int($function) ?
            'Function with parameters ' . implode(',', $function) :
            'Function ' . $function;
        throw new static($msg . " called in {$method} is invalid");
    }
}
