<?php

namespace MovingImage\Client\VM6\Entity;

/**
 * Class ApiCredentials.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class ApiCredentials
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $developerKey;

    /**
     * @var string
     */
    private $clientKey;

    /**
     * ApiCredentials constructor.
     *
     * @param string $apiKey
     * @param string $developerKey
     * @param string $clientKey
     */
    public function __construct($apiKey, $developerKey, $clientKey)
    {
        $this->apiKey = $apiKey;
        $this->developerKey = $developerKey;
        $this->clientKey = $clientKey;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getDeveloperKey()
    {
        return $this->developerKey;
    }

    /**
     * @return string
     */
    public function getClientKey()
    {
        return $this->clientKey;
    }
}
