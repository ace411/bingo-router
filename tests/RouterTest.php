<?php

/**
 * RouterTest class
 * Contains unit tests for the Router functor
 *
 * @package bingo-router
 * @license Apache 2.0
 * @author Lochemem Bruno Michael <lochbm@gmail.com>
 */

use PHPUnit\Framework\TestCase;
use Chemem\Bingo\Router\Router;
use Chemem\Bingo\Router\Collection;
use Chemem\Bingo\Router\RouteTable;

class RouterTest extends TestCase
{
    /**
     * Default route table configuration
     *
     * @var array ROUTE_TABLE_CONFIG
     */

    const ROUTE_TABLE_CONFIG = [
        '{controller}/{action}',
        '{controller}/{action}/{id:\d+}',
        '{controller}/{action}/{id:\d+}/{name:\w+}'
    ];

    /**
     * Default constructor parameters
     *
     * @var array CONSTRUCTOR_PARAMS
     */

    const CONSTRUCTOR_PARAMS = [
        'foo' => 12,
        'bar' => 'baz'
    ];

    /**
     * Tests whether the add method in the Router functor returns a Router object given the same
     * argument when passed via the constructor
     */

    public function testRouterInstanceMatch()
    {
        $queryString = Router::add($GLOBALS['QUERY_STRING']);
        $this->assertSame($queryString->get(), (new Router($GLOBALS['QUERY_STRING']))->get());
    }

    /**
     * Tests whether the Router match method returns an instance of the RouterAbstract class
     */

    public function testRouterMatchReturnsRouterAbstractInstance()
    {
        $queryString = Router::add($GLOBALS['QUERY_STRING'])
            ->match(RouteTable::add(self::ROUTE_TABLE_CONFIG));
        $this->assertInstanceOf(Chemem\Bingo\Router\Common\RouterFunctorAbstract::class, $queryString);
    }

    /**
     * Tests whether the query string matches a standard query string pattern
     */

    public function testQueryStringMatchesStandardUrlFormat()
    {
        $queryString = Router::add($GLOBALS['QUERY_STRING'])->get();
        $this->assertRegExp('/(\/*)([a-zA-Z]*)(\/*)([a-zA-Z0-9]*)/', $queryString);
    }

    /**
     * Tests whether the output returned from a match method call is a string
     */

    public function testMatchedStringIsString()
    {
        $queryString = Router::add($GLOBALS['QUERY_STRING'])
            ->match(RouteTable::add(self::ROUTE_TABLE_CONFIG))
            ->get();
        $this->assertTrue(gettype($queryString) === 'string');
    }

    /**
     * Tests whether the matched string is of a certain format
     */

    public function testMatchedStringIsOfQueryStringFormat()
    {
        $queryString = Router::add($GLOBALS['QUERY_STRING'])
            ->match(RouteTable::add(self::ROUTE_TABLE_CONFIG))
            ->get();
        $this->assertRegExp('/([\/\=\a-z0-9]+)/', $queryString);
    }

    /**
     * Tests whether the dispatch method returns the output of a controller method specified in URL
     */

    public function testRouterDispatchReturnsArbitraryControllerMethod()
    {
        $queryString = Router::add($GLOBALS['QUERY_STRING'])
            ->match(RouteTable::add(self::ROUTE_TABLE_CONFIG))
            ->dispatch(RouteTable::add(['namespace' => 'Chemem\Bingo\Router\Sample\\']));
        $this->assertEquals(
            $queryString,
            (new Chemem\Bingo\Router\Sample\Controller([]))->add()
        );
    }

    /**
     * Tests whether the router dispatch method passes url arguments to controller
     * constructors
     */

    public function testRouterDispatchYieldsUrlParamsInConstructor()
    {
        $queryString = Router::add($GLOBALS['QUERY_STRING'] . '/42')
            ->match(RouteTable::add(self::ROUTE_TABLE_CONFIG))
            ->dispatch(RouteTable::add(['namespace' => 'Chemem\Bingo\Router\Sample\\']));
        $this->assertEquals(
            $queryString,
            (new Chemem\Bingo\Router\Sample\Controller(['id' => '42']))->add()
        );
    }

    /**
     * Tests whether the router dispatch method passes arbitrary arguments
     * to controller constructors
     */

    public function testRouterDispatchYieldsArbitraryParamsInConstructor()
    {
        $queryString = Router::add($GLOBALS['QUERY_STRING'])
            ->match(RouteTable::add(self::ROUTE_TABLE_CONFIG))
            ->dispatch(
                RouteTable::add(['namespace' => 'Chemem\Bingo\Router\Sample\\']),
                Collection::add([
                    'foo' => 'bar',
                    'baz' => 12
                ])
            );
        $this->assertEquals(
            $queryString,
            (new Chemem\Bingo\Router\Sample\Controller([
                'foo' => 'bar',
                'baz' => 12
            ]))
            ->add()
        );
    }
}
