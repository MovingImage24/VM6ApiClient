<?php

namespace MovingImage\Client\VM6\Entity;

class ApiCredentials
{
    private $apiKey;
    private $developerKey;
    private $clientKey;

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
