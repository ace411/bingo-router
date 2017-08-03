<?php

namespace Chemem\Bingo\Router\Common;

interface RouterExceptionInterface
{
    public static function invalidMatch($string, $expected, $method);

    public static function invalidArgument($argument, $expected, $method);

    public static function invalidFunction($function, $method);
}
