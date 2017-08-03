<?php

/**
 * Bingo Router Collection Functor trait
 * Design boilerplate for the classes that implement the IteratorAggregate interface
 *
 * @see Collection::class
 * @see RouteTable::class
 * @package bingo-router
 * @license Apache 2.0
 * @author Lochemem Bruno Michael <lochbm@gmail.com>
 */

namespace Chemem\Bingo\Router\Common;

trait CollectionFunctorTrait
{
    /**
     * Values stored in the Collection functor
     *
     * @access private
     * @var mixed $values
     */

    private $values;

    /**
     * Collection functor constructor
     *
     * @param mixed $values
     */

    public function __construct($values)
    {
        $this->values = $values;
    }

    /**
     * Get instance of array iterator class
     *
     * @see ArrayIterator::class
     * @link http://php.net/manual/en/class.arrayiterator.php
     * @return object ArrayIterator
     */

    public function getIterator()
    {
        return new \ArrayIterator($this->values);
    }
}
