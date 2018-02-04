<?php

namespace Lev\Brewery\Api;

interface ApiClientInterface {

    /**
     * Send request and prepares response
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function execute(RequestInterface $request, ResponseInterface &$response);
}