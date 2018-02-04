<?php

namespace Lev\Brewery\Model;

use InvalidArgumentException;
use Lev\Brewery\Api\RequestInterface;
use Magento\Framework\DataObject;

class DataObjectRequest extends DataObject implements RequestInterface {

    /**
     * Path to data in DataObject
     */
    const DATA_METHOD = 'method';
    const DATA_QUERY = 'query';
    const DATA_PATH = 'path';

    /**
     * List of allowed REST methods
     *
     * @var array
     */
    protected $allowedMethods = [
        'GET',
        'POST',
        'PUT',
        'DELETE'
    ];

    /**
     * Sets method for request
     *
     * @param string $type
     * @return RequestInterface
     * @throws InvalidArgumentException
     */
    public function setMethod(string $type): RequestInterface
    {
        if (!in_array($type, $this->allowedMethods)) {
            throw new InvalidArgumentException('Invalid method for request');
        }

        return $this->setData(self::DATA_METHOD, $type);
    }

    /**
     * Set request data
     *
     * @param array $data
     * @return RequestInterface
     */
    public function setQuery(array $data): RequestInterface
    {
        return $this->setData(self::DATA_QUERY, $data);
    }

    /**
     * Set request path
     *
     * @param string $path
     * @param array $params
     * @return RequestInterface
     */
    public function setPath(string $path, array $params = []): RequestInterface
    {
        if (!empty($params)) {
            $path = $this->processBindings($path, $params);
        }
        return $this->setData(self::DATA_PATH, $path);
    }

    /**
     * Process url bindings
     *
     * @param string $path
     * @param array $params
     * @return string
     */
    protected function processBindings(string $path, array $params) {
        foreach ($params as $key => $value) {
            $path = str_replace(':' . $key, $value, $path);
        }
        return $path;
    }

    /**
     * Get method
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->getData(self::DATA_METHOD);
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->getData(self::DATA_PATH);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getQuery(): array
    {
        return $this->getData(self::DATA_QUERY);
    }
}