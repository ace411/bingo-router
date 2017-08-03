<?php

/**
 * CollectionTest class
 * Contains unit tests for the Collection functor
 * 
 * @package bingo-router
 * @license Apache 2.0
 * @author Lochemem Bruno Michael <lochbm@gmail.com>
 */

use PHPUnit\Framework\TestCase;
use Chemem\Bingo\Router\Collection;

class CollectionTest extends TestCase
{
    /**
     * Default CollectionFunctorTrait
     *
     * @var string ROUTE_TABLE_TRAIT
     */

    const ROUTE_TABLE_TRAIT = 'Chemem\\Bingo\\Router\\Common\\CollectionFunctorTrait';

    /**
     * Parameters to be passed to constructor
     *
     * @var array ROUTE_CONSTRUCTOR_PARAMS
     */

    const ROUTE_CONSTRUCTOR_PARAMS = [
        'foo' => 'bar',
        'baz' => 12
    ];

    /**
     * Tests whether Collection object is the same as when declared with static add method
     */

    public function testCollectionInstanceMatch()
    {
        $table = Collection::add(self::ROUTE_CONSTRUCTOR_PARAMS);
        $this->assertEquals($table, (new Collection(self::ROUTE_CONSTRUCTOR_PARAMS)));
    }

    /**
     * Tests whether the Collection uses the CollectionFunctor trait
     */

    public function testCollectionUsesRouterConstructTrait()
    {
        $table = new Collection(self::ROUTE_CONSTRUCTOR_PARAMS);
        $valid = array_filter(class_uses($table), function ($val) {
            return $val === self::ROUTE_TABLE_TRAIT;
        });
        $count = count($valid);
        $this->assertTrue($count > 0);
    }

    /**
     * Tests whether the Collection class returns an instance of the ArrayIterator class 
     */

    public function testCollectionReturnsIterator()
    {
        $table = Collection::add(self::ROUTE_CONSTRUCTOR_PARAMS)
            ->getIterator();
        $this->assertInstanceOf(ArrayIterator::class, $table);
    }

    /**
     * Tests whether the Collection class accepts only string-indexed arrays
     */

    public function testCollectionIteratorOnlyAcceptsStringKeys()
    {
        $table = iterator_to_array(Collection::add(self::ROUTE_CONSTRUCTOR_PARAMS));
        $valid = rtrim(
            array_reduce(
                array_map(function ($val, $key) {
                    return $val . '=' .  $key . '&';
                }, array_keys($table), array_values($table)),
                function ($carry, $val) {
                    return $carry . $val;
                },
                ""
            ),
            '&'
        );
        $this->assertEquals($valid, "foo=bar&baz=12");
    }

    /**
     * Tests whether Collection class returns a RouterAbstract abstract functor class instance
     */

    public function testAddReturnsRouterAbstractInstance()
    {
        $table = Collection::add(self::ROUTE_CONSTRUCTOR_PARAMS);
        $this->assertInstanceOf(Chemem\Bingo\Router\Common\RouterFunctorAbstract::class, $table);
    }
}
