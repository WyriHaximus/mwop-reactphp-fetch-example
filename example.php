<?php

require 'vendor/autoload.php';

use Clue\React\Buzz\Browser;
use Psr\Http\Message\ResponseInterface;
use React\EventLoop\Factory;
use Rx\Observable;
use function ApiClients\Tools\Rx\observableFromArray;

$loop = Factory::create();
$browser = new Browser($loop);

observableFromArray([
    'https://example.com',
])->flatMap(function (string $url) use ($browser) {
    return Observable::fromPromise($browser->get($url)->then(function (ResponseInterface $response) use ($url) {
        return [
            'response' => $response,
            'url' => $url,
        ];
    }));
})->subscribe(function (array $response) {
    echo $response['url'], PHP_EOL;
    echo $response['response']->getStatusCode(), PHP_EOL;
});

$loop->run();
