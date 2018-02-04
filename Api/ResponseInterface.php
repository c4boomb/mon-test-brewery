<?php

namespace Lev\Brewery\Api;

interface ResponseInterface {

    /**
     * Maps response from API to internal variables
     *
     * @param array $response
     * @return ResponseInterface
     */
    public function map(array $response) : ResponseInterface;

}