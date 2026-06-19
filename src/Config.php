<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk;

/**
 * Configuration object for the Headless.Blog SDK.
 * 
 * Holds the API key and connection settings used by the SDK client.
 */
readonly class Config
{
    /**
     * @param string $apiKey  The secret API key for tenant authentication.
     * @param string $baseUrl The base URL for the Headless.Blog API.
     * @param int    $timeout The HTTP timeout in seconds for API requests.
     */
    public function __construct(
        public string $apiKey,
        public string $baseUrl = 'https://api.headless.blog/v1',
        public int $timeout = 30
    ) {
    }
}
