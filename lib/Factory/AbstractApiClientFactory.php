<?php

namespace MovingImage\Client\VM6\Factory;

use GuzzleHttp\ClientInterface;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use MovingImage\Client\VM6\Entity\ApiCredentials;
use MovingImage\Client\VM6\Interfaces\ApiClientFactoryInterface;
use MovingImage\Client\VM6\Manager\CredentialManager;
use MovingImage\Client\VM6\Util\UnlockTokenGenerator;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractApiClientFactory.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
abstract class AbstractApiClientFactory implements ApiClientFactoryInterface
{
    /**
     * Get the API client class within Guzzle-client specific factories.
     *
     * @return string
     */
    abstract protected function getApiClientClass();

    /**
     * Get the Base URI Guzzle option key - for some reason Guzzle decided
     * to change it between ~5.0 and ~6.0..
     *
     * @return string
     */
    abstract protected function getGuzzleBaseUriOptionKey();

    /**
     * {@inheritdoc}
     */
    public function createCredentialManager(ApiCredentials $credentials)
    {
        return new CredentialManager($credentials);
    }

    /**
     * {@inheritdoc}
     */
    public function createSerializer()
    {
        // Set up that JMS annotations can be loaded through autoloader
        \Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

        return SerializerBuilder::create()->build();
    }

    /**
     * {@inheritdoc}
     */
    public function create(
        ClientInterface $httpClient,
        Serializer $serializer,
        UnlockTokenGenerator $unlockTokenGenerator = null,
        LoggerInterface $logger = null
    ) {
        $cls = $this->getApiClientClass();
        $apiClient = new $cls($httpClient, $serializer, $unlockTokenGenerator);

        if (!is_null($logger)) {
            $apiClient->setLogger($logger);
        }

        return $apiClient;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function createSimple($baseUri, ApiCredentials $credentials, $signKey = '');
}
