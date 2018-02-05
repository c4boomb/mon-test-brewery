<?php

namespace Lev\Brewery\Api;

use InvalidArgumentException;

/**
 * Interface RequestInterface
 * TODO: Implement request in another way
 *
 * @category  Lev
 * @package   Lev\Brewery\Api
 * @author    Lev Grigoryev <lev.grigoryev.al@gmail.com>
 */
interface RequestInterface
{
    /**
     * Sets method for request
     *
     * @param string $type
     * @return RequestInterface
     * @throws InvalidArgumentException
     */
    public function setMethod(string $type): RequestInterface;

    /**
     * Set request data
     *
     * @param array $data
     * @return RequestInterface
     */
    public function setQuery(array $data): RequestInterface;

    /**
     * Set request path
     *
     * @param string $path
     * @param array $params
     * @return RequestInterface
     */
    public function setPath(string $path, array $params = []): RequestInterface;

    /**
     * Get method
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Get data
     *
     * @return array
     */
    public function getQuery(): array;

    /**
     * Get path
     *
     * @return string
     */
    public function getPath(): string;
}