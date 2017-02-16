<?php

namespace MovingImage\Client\VM6\Factory;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use MovingImage\Client\VM6\Entity\ApiCredentials;
use MovingImage\Client\VM6\Manager\CredentialManager;
use MovingImage\Client\VM6\Middleware\ApiKeyMiddleware;
use MovingImage\Client\VM6\ApiClient\Guzzle6ApiClient;

/**
 * Class ApiClientFactory.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class Guzzle6ApiClientFactory extends AbstractApiClientFactory
{
    /**
     * Use the Guzzle6-specific API client class.
     *
     * @return string
     */
    protected function getApiClientClass()
    {
        return Guzzle6ApiClient::class;
    }

    protected function getGuzzleBaseUriOptionKey()
    {
        return 'base_uri';
    }

    /**
     * Instantiate a TokenMiddleware instance with a TokenManager.
     *
     * @param CredentialManager $credentialManager
     *
     * @return ApiKeyMiddleware
     */
    public function createApiKeyMiddleware(CredentialManager $credentialManager)
    {
        return new ApiKeyMiddleware($credentialManager);
    }

    /**
     * Method to instantiate a HTTP client.
     *
     * @param string $baseUri
     * @param array  $middlewares
     * @param array  $options
     *
     * @return ClientInterface
     */
    public function createHttpClient($baseUri, array $middlewares = [], array $options = [])
    {
        $stack = HandlerStack::create();

        foreach ($middlewares as $middleware) {
            $stack->push($middleware);
        }

        return new Client(array_merge([
            'base_uri' => $baseUri,
            'handler' => $stack,
        ], $options));
    }

    /**
     * {@inheritdoc}
     */
    public function createSimple($baseUri, ApiCredentials $credentials)
    {
        $credentialManager = $this->createCredentialManager($credentials);
        $apiKeyMiddleware = $this->createApiKeyMiddleware($credentialManager);
        $httpClient = $this->createHttpClient($baseUri, [$apiKeyMiddleware]);

        return $this->create($httpClient, $this->createSerializer());
    }
}
