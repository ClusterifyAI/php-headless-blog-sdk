<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Resource;

use HeadlessBlog\Sdk\Client;

/**
 * Base abstract class for all SDK resources.
 * 
 * Manages the dependency on the main SDK Client to facilitate HTTP requests.
 */
abstract readonly class AbstractResource
{
    /**
     * @param Client $client The main SDK Client instance.
     */
    public function __construct(
        protected Client $client
    ) {
    }
}
