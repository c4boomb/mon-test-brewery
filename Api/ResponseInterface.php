<?php

namespace Lev\Brewery\Api;

/**
 * Interface ResponseInterface
 *
 * @category  Lev
 * @package   Lev\Brewery\Api
 * @author    Lev Grigoryev <lev.grigoryev.al@gmail.com>
 */
interface ResponseInterface
{
    /**
     * Maps response from API to internal variables
     *
     * @param array $response
     * @return ResponseInterface
     */
    public function map(array $response): ResponseInterface;
}