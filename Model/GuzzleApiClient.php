<?php

namespace Lev\Brewery\Model;

use GuzzleHttp\ClientInterface;
use Lev\Brewery\Api\ApiClientInterface;
use Lev\Brewery\Api\RequestInterface;
use Lev\Brewery\Api\ResponseInterface;
use Lev\Brewery\Exception\ApiFailure;
use Magento\Framework\App\Config\ScopeConfigInterface;

class GuzzleApiClient implements ApiClientInterface {

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
        $response = $this->guzzle->request(
            $request->getMethod(),
            $this->getRequestPath($request),
            [
                'query' => $request->getQuery()
            ]
        );

        $response = json_decode($response, true);

        if ($response['status'] == 'failure') {
            throw new ApiFailure($response, $response['errorMessage'] ?? '');
        }

        $response->map($response);
    }

    protected function getRequestPath(RequestInterface $request) : string {
        $url = sprintf(
            '%s%s?key=%s',
            $this->getBaseUrl(),
            $request->getPath(),
            $this->getApiKey()
        );
    }

    protected function getBaseUrl() : string {
        return $this->scopeConfig->getValue(self::XML_PATH_API_URL);
    }

    protected function getApiKey() : string {
        return $this->scopeConfig->getValue(self::XML_PATH_API_KEY);
    }
}