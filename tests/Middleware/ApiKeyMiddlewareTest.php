<?php

namespace MovingImage\Test\Client\VM6\Middleware;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use MovingImage\Client\VM6\Entity\ApiCredentials;
use MovingImage\Client\VM6\Manager\CredentialManager;
use MovingImage\Client\VM6\Middleware\ApiKeyMiddleware;

class ApiKeyMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    private $credentials;
    private $client;
    private $historyContainer;

    private function createJsonStream($arr)
    {
        $str = json_encode($arr);
        $stream = \GuzzleHttp\Psr7\stream_for($str);

        return $stream;
    }

    public function setUp()
    {
        if (version_compare(ClientInterface::VERSION, '6.0', '<')) {
            $this->markTestSkipped('Skipping tests for Guzzle6ApiClient when Guzzle ~5.0 is installed');

            return;
        }

        $this->historyContainer = [];
        $this->credentials = new ApiCredentials('dfkljgdfklgjdklgjdfkl', 'jkdfghjkdfghjkdfhg', 'jksdfkldsfjkl');

        $stack = HandlerStack::create(new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $this->createJsonStream([
                'id' => 5,
                'name' => 'root_channel',
            ])),
        ]));

        $stack->push(new ApiKeyMiddleware(new CredentialManager($this->credentials)));
        $stack->push(Middleware::history($this->historyContainer));

        $this->client = new Client([
            'base_uri' => 'http://example.com/api/v1/',
            'handler' => $stack,
        ]);
    }

    public function testRequestGetsRightUriParameters()
    {
        $this->client->request('GET', 'test');
        $requestUri = (string) $this->historyContainer[0]['request']->getUri();

        $this->assertEquals(
            sprintf('http://example.com/api/v1/test?api_key=%s&developer_key=%s&client_key=%s',
                'dfkljgdfklgjdklgjdfkl',
                'jkdfghjkdfghjkdfhg',
                'jksdfkldsfjkl'
            ),
            $requestUri
        );
    }

    public function testRequestGetsRightUriParametersWithAlreadyUriParams()
    {
        $this->client->request('GET', 'test?id=3');
        $requestUri = (string) $this->historyContainer[0]['request']->getUri();

        $this->assertEquals(
            sprintf('http://example.com/api/v1/test?id=3&api_key=%s&developer_key=%s&client_key=%s',
                'dfkljgdfklgjdklgjdfkl',
                'jkdfghjkdfghjkdfhg',
                'jksdfkldsfjkl'
            ),
            $requestUri
        );
    }
}
