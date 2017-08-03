<?php

use PHPUnit\Framework\TestCase;
use Chemem\Bingo\Router\RouteTable;

class RouteTableTest extends TestCase
{
    const ROUTE_TABLE_CONFIG = [
        '{controller}/{action}',
        '{controller}/{action}/{id:\d+}'
    ];

    const ROUTE_TABLE_TRAIT = 'Chemem\\Bingo\\Router\\Common\\CollectionFunctorTrait';

    public function testRouteTableUsesRouterConstructTrait()
    {
        $table = new RouteTable(self::ROUTE_TABLE_CONFIG);
        $valid = array_filter(class_uses($table), function ($val) {
            return $val === self::ROUTE_TABLE_TRAIT;
        });
        $count = count($valid);
        $this->assertTrue($count === 1);
    }

    public function testRouteTableReturnsIterator()
    {
        $table = RouteTable::add(self::ROUTE_TABLE_CONFIG)
            ->getIterator();
        $this->assertInstanceOf(ArrayIterator::class, $table);
    }

    public function testRouteTableReturnsControllerMatchString()
    {
        $matchStrings = RouteTable::add(self::ROUTE_TABLE_CONFIG)
            ->map(function ($values) {
                return array_filter(iterator_to_array($values), function ($val) {
                    return preg_match('/([a-zA-Z0-9\$\/]+)/', $val);
                });
            });
        $count = count(iterator_to_array($matchStrings));
        $this->assertTrue($count > 0);
    }

    public function testRouteTableReturnsRouterAbstractInstance()
    {
        $table = RouteTable::add(self::ROUTE_TABLE_CONFIG);
        $this->assertInstanceOf(Chemem\Bingo\Router\Common\RouterFunctorAbstract::class, $table);
    }
}
