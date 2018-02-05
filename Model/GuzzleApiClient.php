<?php

namespace Lev\Brewery\Model;

use GuzzleHttp\ClientInterface;
use Lev\Brewery\Api\ApiClientInterface;
use Lev\Brewery\Api\RequestInterface;
use Lev\Brewery\Api\ResponseInterface;
use Lev\Brewery\Exception\ApiFailure;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class GuzzleApiClient
 *
 * @category  Lev
 * @package   Lev\Brewery\Model
 * @author    Lev Grigoryev <lev.grigoryev.al@gmail.com>
 */
class GuzzleApiClient implements ApiClientInterface
{
    /**
     * Path to config
     */
    const XML_PATH_API_URL = 'brew/api/url';
    const XML_PATH_API_KEY = 'brew/api/api_key';

    /**
     * @var ClientInterface
     */
    protected $guzzle;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * GuzzleApiClient constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param ClientInterface $guzzle
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ClientInterface $guzzle
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->guzzle = $guzzle;
    }

    /**
     * Send request and prepares response
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @throws ApiFailure
     */
    public function execute(RequestInterface $request, ResponseInterface &$response)
    {
        $query = $request->getQuery();
        $query['key'] = $this->getApiKey();
        $guzzleResponse = $this->guzzle->request(
            $request->getMethod(),
            $this->getRequestPath($request),
            [
                'query' => $query
            ]
        );

        $guzzleResponse = json_decode($guzzleResponse->getBody(), true);

        if ($guzzleResponse['status'] == 'failure') {
            throw new ApiFailure($guzzleResponse, $guzzleResponse['errorMessage'] ?? '');
        }

        $response->map($guzzleResponse);
    }

    /**
     * @return string
     */
    protected function getApiKey(): string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_API_KEY);
    }

    /**
     * @param RequestInterface $request
     * @return string
     */
    protected function getRequestPath(RequestInterface $request): string
    {
        return $this->getBaseUrl() . $request->getPath();
    }

    /**
     * @return string
     */
    protected function getBaseUrl(): string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_API_URL);
    }
}