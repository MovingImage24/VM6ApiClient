# Video Manager 6 PHP API Client

[![Build Status](https://travis-ci.org/MovingImage24/VM6ApiClient.svg?branch=master)](https://travis-ci.org/MovingImage24/VM6ApiClient) [![License](https://poser.pugx.org/movingimage/vm6-api-client/license)](https://packagist.org/packages/movingimage/vm6-api-client) [![Latest Unstable Version](https://poser.pugx.org/movingimage/vm6-api-client/v/unstable)](https://packagist.org/packages/movingimage/vm6-api-client) [![Latest Stable Version](https://poser.pugx.org/movingimage/vm6-api-client/v/stable)](https://packagist.org/packages/movingimage/vm6-api-client)

## Installation

To install the API client, run the following command:

```
$ composer require movingimage/vm6-api-client
```

## Usage

To use the VM6 API Client, you can use the factory like this:

```php
<?php

use MovingImage\Client\VM6\Entity\ApiCredentials;
use MovingImage\Client\VM6\ApiClientFactory;

require_once('./vendor/autoload.php');

$baseUri     = 'https://<api uri>';
$credentials = new ApiCredentials('<api key>', '<developer key>', '<client key>');
$factory     = new ApiClientFactory();

$apiClient = $factory->createSimple($baseUri, $credentials);

echo $apiClient->getChannels(5)->getName() . PHP_EOL;
```

Guzzle 5 is currently not supported.

## Maintainers

* Ruben Knol - ruben.knol@movingimage.com

If you have questions, suggestions or problems, feel free to get in touch with the maintainers by e-mail.

## Contributing

If you want to expand the functionality of the API clients, or fix a bug, feel free to fork and do a pull request back onto the 'master' branch. Make sure the tests pass.
