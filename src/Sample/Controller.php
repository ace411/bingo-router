<?php

/**
 * Bingo Router sample Controller class
 * Controller used to demonstrate how to effectively use the router
 *
 * @package bingo-router
 * @license Apache 2.0
 * @author Lochemem Bruno Michael <lochbm@gmail.com>
 */

namespace Chemem\Bingo\Router\Sample;

use Chemem\Bingo\Router\Common\RouterControllerInterface;
use Chemem\Bingo\Router\Common\RouterControllerTrait;

class Controller implements RouterControllerInterface
{
    /**
     * The RouterConstruct trait
     * Mandatory for all controllers using the router software
     */

    use RouterControllerTrait;

    /**
     * constructor values
     *
     * @access private
     * @var array $values
     */

    private $values;

    /**
     * Controller constructor
     *
     * @param array $values
     */

    public function __construct($values)
    {
        $this->values = $values;
    }

    /**
     * index method
     *
     * @return int $values[id]
     */

    public function index()
    {
        return isset($this->values['id']) ? (int) $this->values['id'] : 19;
    }

    /**
     * add method
     *
     * @return int $sum
     */

    public function add() : int
    {
        return isset($this->values['baz']) ? $this->values['baz'] + 12 : 25;
    }

    /**
     * get method
     *
     * @return array $values
     */

    public function get()
    {
        return $this->values;
    }
}
