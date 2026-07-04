<?php

declare(strict_types=1);

use Misaf\VendraFaqApi\JsonApi\V1\Server;
use Misaf\VendraFaqApi\Tests\TestCase;

pest()->extend(TestCase::class);

it('uses the registered faq api base uri', function (): void {
    $properties = (new ReflectionClass(Server::class))->getDefaultProperties();

    expect($properties['baseUri'])->toBe('/v1');
});
