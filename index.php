<?php

require __DIR__ . '/vendor/autoload.php';

use Chemem\Bingo\Router\Router;
use Chemem\Bingo\Router\RouteTable;
use Chemem\Bingo\Router\Collection;
use Chemem\Bingo\Router\Sample\Controller;
use function Chemem\Bingo\Router\Helpers\compose;

define('PARAM_QUERY_STRING', 'controller/');

$router = Router::add(
        isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : PARAM_QUERY_STRING
    )
    ->match(RouteTable::add([
        '{controller}/{action}',
        '{controller}/{action}/{id:\d+}',
        '{controller}/{id:\d+}',
        '{controller}/{action}/{name:\w+}'
    ]))
    ->dispatch(
        RouteTable::add([
            'namespace' => 'Chemem\Bingo\Router\Sample\\'
        ]),
        Collection::add([
            'foo' => 'bar',
            'baz' => 12
        ])
    );

echo $router;
