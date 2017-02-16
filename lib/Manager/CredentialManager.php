<?php

namespace MovingImage\Client\VM6\Manager;

use MovingImage\Client\VM6\Entity\ApiCredentials;
use MovingImage\Client\VM6\Traits\LoggerAwareTrait;
use Psr\Log\LoggerAwareInterface;

/**
 * Class CredentialManager.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class CredentialManager implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var ApiCredentials
     */
    protected $credentials;

    /**
     * CredentialManager constructor.
     *
     * @param ApiCredentials  $credentials
     */
    public function __construct(
        ApiCredentials $credentials
    ) {
        $this->credentials = $credentials;
    }

    /**
     * Retrieve the credentials.
     *
     * @return ApiCredentials
     */
    public function getCredentials()
    {
        return $this->credentials;
    }
}
