<?php

namespace MovingImage\Client\VM6\Middleware;

use GuzzleHttp\Psr7\Uri;
use MovingImage\Client\VM6\Manager\CredentialManager;
use MovingImage\Client\VM6\Traits\LoggerAwareTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerAwareInterface;

/**
 * Class ApiKeyMiddleware.
 *
 * @author Ruben Knol <ruben.knol@movingimage.com>
 */
class ApiKeyMiddleware implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @const string
     */
    const PARAM_API_KEY = 'api_key';

    /**
     * @const string
     */
    const PARAM_DEVELOPER_KEY = 'developer_key';

    /**
     * @const string
     */
    const PARAM_CLIENT_KEY = 'client_key';

    /**
     * @var CredentialManager
     */
    private $credentialManager;

    /**
     * TokenMiddleware constructor.
     *
     * @param CredentialManager $credentialManager
     */
    public function __construct(CredentialManager $credentialManager)
    {
        $this->credentialManager = $credentialManager;
    }

    private function appendQueryParameter(RequestInterface $request, $key, $value)
    {
        return $request->withUri(Uri::withQueryValue($request->getUri(), $key, $value));
    }

    /**
     * Middleware invocation method that actually adds the bearer
     * token to the HTTP request.
     *
     * @param callable $handler
     *
     * @return \Closure
     */
    public function __invoke(callable $handler)
    {
        return function (
            RequestInterface $request,
            array $options
        ) use ($handler) {
            $credentials = $this->credentialManager->getCredentials();

            $request = $this->appendQueryParameter($request, self::PARAM_API_KEY, $credentials->getApiKey());
            $request = $this->appendQueryParameter($request, self::PARAM_DEVELOPER_KEY, $credentials->getDeveloperKey());
            $request = $this->appendQueryParameter($request, self::PARAM_CLIENT_KEY, $credentials->getClientKey());

            return $handler($request, $options);
        };
    }
}
