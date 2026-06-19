<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Resource;

/**
 * Handles the Website Settings API endpoints.
 */
readonly class SettingsResource extends AbstractResource
{
    /**
     * Retrieves general website settings.
     * 
     * Mobile apps and decoupled frontends can call this to retrieve the base configuration
     * like the website name, URLs, and AI feature toggles.
     *
     * @return array<string, mixed> The settings object containing website configuration.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function get(): array
    {
        return $this->client->request('GET', 'settings');
    }
}
