<?php

namespace Lev\Brewery\Model\Request;

use Lev\Brewery\Api\Request\BuilderInterface;
use Lev\Brewery\Api\RequestInterface;
use Lev\Brewery\Api\RequestInterfaceFactory;

/**
 * Class Builder
 *
 * @category  Lev
 * @package   Lev\Brewery\Model\Builder
 * @author    Lev Grigoryev <lev.grigoryev.al@gmail.com>
 */
class Builder implements BuilderInterface
{
    /**
     * @var RequestInterfaceFactory
     */
    private $requestFactory;

    /**
     * Builder constructor
     *
     * @param RequestInterfaceFactory $requestFactory
     */
    public function __construct(RequestInterfaceFactory $requestFactory)
    {
        $this->requestFactory = $requestFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function build(string $path, string $method, array $query = [], array $params = []): RequestInterface
    {
        return $this->requestFactory->create()
            ->setPath($path, $params)
            ->setMethod($method)
            ->setQuery($query);
    }
}