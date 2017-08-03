<?php

/**
 * Bingo Router Collection class
 * Useful for manipulating collections with string indexes
 *
 * @package bingo-router
 * @license Apache 2.0
 * @author Lochemem Bruno Michael <lochbm@gmail.com>
 */

namespace Chemem\Bingo\Router;

use Chemem\Bingo\Router\Common\RouterFunctorAbstract;
use Chemem\Bingo\Router\Common\CollectionFunctorTrait;
use Chemem\Bingo\Router\Exceptions\InvalidArgumentException;

class Collection extends RouterFunctorAbstract implements \IteratorAggregate
{
    use CollectionFunctorTrait;

    /**
     * Collection add method
     *
     * @param mixed $values The values to be stored in the functor
     * @return object RouterFunctorAbstract A collection object
     */

    public static function add($values) : RouterFunctorAbstract
    {
        if ($values instanceof Traversable) {
            $values = iterator_to_array($values);
        } elseif (!is_array($values)) {
            $values = [$values];
        }
        //only parameters with string indexes
        $values = !empty(array_filter(array_keys($values), function ($val) {
            return gettype($val) === 'string'; //only indexed strings
        })) ? 
        $values :
        InvalidArgumentException::invalidArgument($values, [], __METHOD__);
        return new static($values);
    }
}
