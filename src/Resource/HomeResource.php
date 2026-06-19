<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Resource;

/**
 * Handles the Consolidated Homepage Data API endpoints.
 */
readonly class HomeResource extends AbstractResource
{
    /**
     * Aggregates all homepage sections.
     * 
     * Useful to build a full landing page in a single request, preventing UI waterfall loading.
     *
     * @return array<string, mixed> The homepage data containing different sections like hero, recent, featured.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function get(): array
    {
        return $this->client->request('GET', 'home');
    }
}
