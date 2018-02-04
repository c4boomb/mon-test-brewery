<?php

namespace Lev\Brewery\Exception;

use Exception;
use Throwable;

class ApiFailure extends Exception {
    /**
     * @var mixed
     */
    private $response;

    /**
     * ApiFailure constructor
     *
     * @param array $response
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($response = [], $message = "", $code = 0, Throwable $previous = null)
    {
        $this->response = $response;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getResponse() {
        return $this->response;
    }
}