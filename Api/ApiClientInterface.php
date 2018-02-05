<?php

namespace Lev\Brewery\Api;

/**
 * Interface ApiClientInterface
 *
 * @category  Lev
 * @package   Lev\Brewery\Api
 * @author    Lev Grigoryev <lev.grigoryev.al@gmail.com>
 */
interface ApiClientInterface
{
    /**
     * Send request and prepares response
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function execute(RequestInterface $request, ResponseInterface &$response);
}