<?php

namespace MovingImage\Client\VM6\Interfaces;

use GuzzleHttp\ClientInterface;
use JMS\Serializer\Serializer;
use MovingImage\Client\VM6\Entity\ApiCredentials;
use MovingImage\Client\VM6\Manager\CredentialManager;
use Psr\Log\LoggerInterface;

/**
 * Interface ApiClientFactoryInterface.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
interface ApiClientFactoryInterface
{
    const VERSION = '0.1.0';

    /**
     * Instantiate a TokenManager with a set of API credentials.
     *
     * @param ApiCredentials $credentials
     *
     * @return CredentialManager
     */
    public function createCredentialManager(ApiCredentials $credentials);

    /**
     * Method to instantiate a serializer instance.
     *
     * @return \JMS\Serializer\Serializer
     */
    public function createSerializer();

    /**
     * Factory method to create a new instance of the VMPro
     * API Client.
     *
     * @param ClientInterface      $httpClient
     * @param Serializer           $serializer
     * @param LoggerInterface|null $logger
     *
     * @return ApiClientInterface
     */
    public function create(
        ClientInterface $httpClient,
        Serializer $serializer,
        LoggerInterface $logger = null
    );

    /**
     * Abstraction to more simpler instantiate an API client.
     *
     * @param string         $baseUri
     * @param ApiCredentials $credentials
     *
     * @return ApiClientInterface
     */
    public function createSimple($baseUri, ApiCredentials $credentials);
}
