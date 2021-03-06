<?php

namespace MovingImage\Client\VM6\ApiClient;

use GuzzleHttp\ClientInterface;
use JMS\Serializer\Serializer;
use MovingImage\Client\VM6\Exception;
use MovingImage\Client\VM6\Traits\LoggerAwareTrait;
use MovingImage\Client\VM6\Util\UnlockTokenGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;

/**
 * Class AbstractCoreApiClient.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
abstract class AbstractCoreApiClient implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var ClientInterface The Guzzle HTTP client
     */
    protected $httpClient;

    /**
     * @var Serializer The JMS Serializer instance
     */
    protected $serializer;

    /**
     * @var UnlockTokenGenerator
     */
    protected $unlockTokenGenerator;

    /**
     * ApiClient constructor.
     *
     * @param ClientInterface      $httpClient
     * @param Serializer           $serializer
     * @param UnlockTokenGenerator $unlockTokenGenerator
     */
    public function __construct(
        ClientInterface $httpClient,
        Serializer $serializer,
        UnlockTokenGenerator $unlockTokenGenerator = null
    ) {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->unlockTokenGenerator = $unlockTokenGenerator;
    }

    /**
     * Perform the actual request in the implementation classes.
     *
     * @param string $method
     * @param string $uri
     * @param array  $options
     *
     * @return mixed
     */
    abstract protected function _doRequest($method, $uri, $options);

    /**
     * Make a request to the API and serialize the result according to our
     * serialization strategy.
     *
     * @param string $method
     * @param string $uri
     * @param array  $options
     *
     * @return object|ResponseInterface
     */
    protected function makeRequest($method, $uri, $options)
    {
        $logger = $this->getLogger();

        try {
            $logger->info(sprintf('Making API %s request to %s', $method, $uri), [$options]);

            /** @var ResponseInterface $response */
            $response = $this->_doRequest($method, $uri, $options);

            $logger->debug('Response from HTTP call was status code:', [$response->getStatusCode()]);
            $logger->debug('Response JSON was:', [$response->getBody()]);

            return $response;
        } catch (\Exception $e) {
            throw $e; // Just rethrow for now
        }
    }

    /**
     * Deserialize a response into an instance of it's associated class.
     *
     * @param string $data
     * @param string $serialisationClass
     *
     * @return object
     */
    protected function deserialize($data, $serialisationClass)
    {
        return $this->serializer->deserialize($data, $serialisationClass, 'json');
    }

    /**
     * Helper method to build the JSON data array for making a request
     * with ::makeRequest(). Optional parameters with empty or null value will be
     * omitted from the return value.
     *
     * Examples:
     *
     * $this->buildJsonParameters(['title' => 'test'], ['description' => '', 'bla' => 'test'])
     *
     * Would result in:
     *
     * [
     *     'title' => 'test',
     *     'bla' => 'test',
     * ]
     *
     * @param array $required
     * @param array $optional
     *
     * @return array
     */
    protected function buildJsonParameters(array $required, array $optional)
    {
        foreach ($required as $key => $value) {
            if (empty($value)) {
                throw new Exception(sprintf('Required parameter \'%s\' is missing..', $key));
            }
        }

        $json = $required;

        foreach ($optional as $key => $value) {
            if (!empty($value) || $value === false) {
                $json[$key] = $value;
            }
        }

        return $json;
    }
}
