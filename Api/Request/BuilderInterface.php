<?php

namespace Lev\Brewery\Api\Request;

use Lev\Brewery\Api\RequestInterface;

/**
 * Interface BuilderInterface
 *
 * @category  Lev
 * @package   Lev\Brewery\Api\Request
 * @author    Lev Grigoryev <lev.grigoryev.al@gmail.com>
 */
interface BuilderInterface
{
    /**
     * Builds request object
     *
     * @param string $path
     * @param string $method
     * @param array $query
     * @param array $params
     * @return RequestInterface
     */
    public function build(string $path, string $method, array $query = [], array $params = []): RequestInterface;
}