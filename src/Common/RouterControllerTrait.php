<?php

namespace Chemem\Bingo\Router\Common;

use Chemem\Bingo\Router\Exceptions\InvalidArgumentException;

trait RouterControllerTrait
{
    public function __call($method, $args)
    {
        //check if method exists
        $callable = method_exists($this, $method) ?
            call_user_func_array([$this, $method], $args) :
            InvalidArgumentException::invalidFunction($method, __METHOD__);
    }
}
