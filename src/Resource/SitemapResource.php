<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Resource;

/**
 * Handles the Sitemap Generation API endpoints.
 */
readonly class SitemapResource extends AbstractResource
{
    /**
     * Dynamically generates standard sitemap data (URLs, last modification dates, priorities).
     * 
     * Used by decoupled frontends to programmatically render dynamic sitemap.xml feeds.
     *
     * @return array<int, mixed> An array of sitemap URL objects.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function list(): array
    {
        return $this->client->request('GET', 'sitemap');
    }
}
