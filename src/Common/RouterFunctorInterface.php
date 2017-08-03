<?php

namespace Chemem\Bingo\Router\Common;

interface RouterFunctorInterface
{
    public function map(callable $fn) : RouterFunctorInterface;
}
