<?php

/**
 * Bingo Router RouterControllerInterface
 * Design contract for the bingo-router controller classes
 *
 * @package bingo-router
 * @license Apache 2.0
 * @author Lochemem Bruno Michael <lochbm@gmail.com>
 */

namespace Chemem\Bingo\Router\Common;

interface RouterControllerInterface
{
    /**
     * index method
     * Default controller function
     */

    public function index();

    /**
     * get method
     * fetch constructor defined args
     */

    public function get();
}
