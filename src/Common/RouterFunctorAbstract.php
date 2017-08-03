<?php

/**
 * Bingo-Router RouterFunctorAbstract class
 * The functor abstract class
 *
 * @package bingo-router
 * @license Apache 2.0
 * @author Lochemem Bruno Michael <lochbm@gmail.com>
 */

namespace Chemem\Bingo\Router\Common;

use Chemem\Bingo\Router\Exceptions\InvalidArgumentException;

abstract class RouterFunctorAbstract implements RouterFunctorInterface
{
    /**
     * Add function for storing values in functor
     *
     * @abstract
     * @param mixed $value The value to be stored by functor
     * @return object RouterFunctorAbstract
     */

    public abstract static function add($value) : RouterFunctorAbstract;

    /**
     * Map method for manipulating stored values
     *
     * @param callable $fn The function onto which the stored value is mapped
     * @return object RouterFunctorInterface
     */

    public function map(callable $fn) : RouterFunctorInterface
    {
        return (new \ReflectionFunction($fn))->isUserDefined() ?
            $this->add($fn($this)) :
            InvalidArgumentException::invalidFunction($fn, __METHOD__);
    }
}
