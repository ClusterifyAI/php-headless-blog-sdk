<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Resource;

/**
 * Handles the Application Bootstrap & Navigation API endpoints.
 */
readonly class BootstrapResource extends AbstractResource
{
    /**
     * Retrieves all foundational categories, tags, and multi-dimensional taxonomies.
     * 
     * Mobile apps and decoupled frontends should call this once on launch to build their
     * drawer navigation and filter menus.
     *
     * @return array<string, mixed> The bootstrap configuration containing categories, tags, taxonomies, and postTypes.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function get(): array
    {
        return $this->client->request('GET', 'init');
    }
}
