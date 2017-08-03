<?php

/**
 * Bingo Router Router class
 * Eponymous with the package; useful for mapping routes onto controllers
 *
 * @package bingo-router
 * @license Apache 2.0
 * @author Lochemem Bruno Michael <lochbm@gmail.com>
 */

namespace Chemem\Bingo\Router;

use Chemem\Bingo\Router\Common\RouterFunctorAbstract;
use Chemem\Bingo\Router\Exceptions\InvalidArgumentException;
use function Chemem\Bingo\Router\Helpers\compose;

class Router extends RouterFunctorAbstract
{
    /**
     * queryString attribute
     *
     * @access private
     * @var mixed queryString
     */

    private $queryString;

    /**
     * Default controller interface
     *
     * @var ROUTER_CONTROLLER_INTERFACE
     */

    const ROUTER_CONTROLLER_INTERFACE = 'Chemem\\Bingo\\Router\\Common\\RouterControllerInterface';

    /**
     * Default controller trait
     *
     * @var ROUTER_CONTROLLER_TRAIT
     */

    const ROUTER_CONTROLLER_TRAIT = "Chemem\\Bingo\\Router\\Common\\RouterControllerTrait";

    /**
     * Query String regex used in multiple matches
     *
     * @var QUERY_STRING_REGEX
     */

    const QUERY_STRING_REGEX = '/([\/]+)/';

    /**
     * Router functor constructor
     *
     * @param mixed $queryString
     */

    public function __construct($queryString)
    {
        $this->queryString = $queryString;
    }

    /**
     * Router add method
     *
     * @param string $queryString The queryString to be tested against
     * @return object RouterFunctorAbstract
     */

    public static function add($queryString) : RouterFunctorAbstract
    {
        $queryString = preg_match('/(\/*)([a-zA-Z]*)(\/*)([a-zA-Z0-9]*)/', $queryString) ?
            //strip the parameter components in the query string
            $queryString :
            InvalidArgumentException::invalidMatch(
                $queryString,
                '(\/*)([a-zA-Z]*)(\/*)([a-zA-Z0-9]*)',
                __METHOD__
            );
        return new static($queryString);
    }

    /**
     * Router match method
     *
     * @param object RouterFunctorAbstract $routes The RouteTable routes to be compared against
     * @return object RouterFunctorAbstract $queryString
     */

    public function match(RouterFunctorAbstract $routes) : RouterFunctorAbstract
    {
        $routes = $routes instanceof \Traversable ?
            iterator_to_array($routes) :
            InvalidArgumentException::invalidArgument($routes, func_get_arg(0), __METHOD__);
        $queryString = $this->get();
        //compose a function that maps the matches in the regex to the controller and method to the route
        $matched = array_filter($routes, function ($value) use (&$queryString) {
            return preg_match($value, $queryString);
        });
        $stripFunction = compose(
            function (string $val) {
                return preg_replace('/([\^\?\(\)\[\]\+\-\$]+)/', '', rtrim($val, 'i'));
            },
            function (string $val) {
                return str_replace('az', '', ltrim($val, '/'));
            },
            function (string $val) {
                return preg_replace('/(\\\\*)/', '', $val);
            },
            function (string $val) {
                return str_replace('d/', '', $val);
            },
            function (string $val) {
                return str_replace('P', '', $val);
            }
        );
        $stripped = array_map($stripFunction, $matched);
        return new static(
            array_reduce($stripped, function ($carry, $val) {
                $carry = preg_match(self::QUERY_STRING_REGEX, $carry) ? $carry : $carry . '/';
                $match = array_map(
                    function ($q, $s) {
                        return "{$q}={$s}/";
                    },
                    preg_split(self::QUERY_STRING_REGEX, $val),
                    preg_split(self::QUERY_STRING_REGEX, $carry)
                );
                return array_reduce($match, function ($carry, $val) {
                    return $carry . $val;
                }, "");
            }, $queryString)
        );
    }

    /**
     * Router dispatch method
     *
     * @param object RouterFunctorAbstract $params
     */

    public function dispatch(RouterFunctorAbstract $params, RouterFunctorAbstract $construct = null)
    {
        $params = $params instanceof \Traversable ?
            iterator_to_array($params) :
            InvalidArgumentException::invalidArgument($routes, func_get_arg(0), __METHOD__);
        $construct = !is_null($construct) && $construct instanceof \Traversable ?
            iterator_to_array($construct) :
            []; //set to empty array if null
        $url = preg_match(self::QUERY_STRING_REGEX, $this->get()) ? $this->get() : $this->get() . '/';
        //two cases: one string with numerous instances of the equal sign
        //another with no equal sign
        $components = preg_match('/([\=\<\>]+)/', $url) ?
            array_map(
                function ($val) {
                    return preg_replace('/([\<\>]+)/', '', $val);
                }, array_filter(
                    explode('/', rtrim(rtrim($url, '/'), '=')),
                    function ($val) {
                        return $val !== "=/";
                    }
                )
            ) :
            ['controller=' . rtrim($url, '/'), 'action=index'];
        //array_merge the values feature the controller in their query string
        $controller = array_reduce(
            array_filter($components, function ($val) {
                return strpos($val, 'controller') === false ? false : true;
            }),
            function ($carry, $val) {
                return str_replace('controller=', '', $carry . $val);
            },
            ""
        );
        $method = array_filter($components, function ($val) {
            return strpos($val, 'action') === false ? false : true;
        });
        $methodCount = count($method);
        $method = $methodCount > 0 ?
            array_reduce($method, function ($carry, $val) {
                return str_replace('action=', '', $carry . $val);
            }, "") :
            'index';
        $arbitrary = implode(
            '&',
            array_filter($components, function ($val) {
                return strpos($val, 'controller') === false && strpos($val, 'action') === false;
            })
        );
        $controller = preg_replace("/([\/$\^]+)/", "", rtrim($params['namespace'], 'i')) .
            str_replace(' ', '', ucwords(str_replace('-', ' ', $controller)));
        $method = isset($method) || $method !== "" || mb_strlen($method) !== 0 ?
            lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $method)))) :
            'index'; //not specifying a method will revert to an index function
        $controllerFunction = compose(
            function (string $controller) {
                return array_filter(
                    class_implements($controller),
                    function ($val) {
                        return $val === Router::ROUTER_CONTROLLER_INTERFACE;
                    }
                ) ?
                $controller :
                null;
            },
            function (string $controller) {
                return array_filter(
                    class_uses($controller),
                    function ($val) {
                        return $val === Router::ROUTER_CONTROLLER_TRAIT;
                    }
                ) ?
                $controller :
                null;
            }
        );
        $controller = array_reduce(
            array_map($controllerFunction, [$controller]),
            function ($carry, $val) {
                return $carry . $val;
            },
            ""
        );
        $urlArgs = isset($arbitrary) || !is_null($arbitrary) ? parse_str($arbitrary, $_FETCH) : [];
        $object = class_exists($controller) ?
            new $controller(array_merge($_FETCH, $construct)) :
            InvalidArgumentException::invalidFunction("{$controller}()", __METHOD__);
        return is_callable([$object, $method]) ?
            $object->$method() :
            InvalidArgumentException::invalidFunction($method, __METHOD__);
    }

    /**
     * Router get method
     *
     * @return mixed $queryString
     */

    public function get()
    {
        return $this->queryString;
    }
}
