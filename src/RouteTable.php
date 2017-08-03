<?php

/**
 * Bingo Router RouteTable class
 * Useful for creating query string matches
 *
 * @package bingo-router
 * @license Apache 2.0
 * @author Lochemem Bruno Michael <lochbm@gmail.com>
 */

namespace Chemem\Bingo\Router;

use function Chemem\Bingo\Router\Helpers\compose;
use Chemem\Bingo\Router\Common\RouterFunctorAbstract;
use Chemem\Bingo\Router\Common\CollectionFunctorTrait;

class RouteTable extends RouterFunctorAbstract implements \IteratorAggregate
{
    use CollectionFunctorTrait;

    /**
     * RouteTable add method
     *
     * @param mixed $routes The routes to be stored in functor
     * @return object RouterFunctorAbstract A RouteTable object
     */

    public static function add($routes) : RouterFunctorAbstract
    {
        if ($routes instanceof Traversable) {
            $routes = iterator_to_array($routes);
        } elseif (!is_array($routes)) {
            $routes = [$routes];
        }
        $composedRouteFunction = compose(
            function (string $route) : string {
                return preg_replace('/\//', '\\/', $route);
            },
            function (string $route) : string {
                return preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
            },
            function (string $route) : string {
                return preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
            },
            function (string $route) : string {
                return '/^' . $route . '$/i';
            }
        );
        return new static(array_map($composedRouteFunction, $routes));
    }
}
