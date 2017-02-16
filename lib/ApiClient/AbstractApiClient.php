<?php

namespace MovingImage\Client\VM6\ApiClient;

use MovingImage\Client\VM6\Interfaces\ApiClientInterface;
use MovingImage\Client\VM6\Traits\LoggerAwareTrait;

abstract class AbstractApiClient extends AbstractCoreApiClient implements ApiClientInterface
{
    use LoggerAwareTrait;
}