<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Resource;

/**
 * Handles the Static Pages API endpoints.
 */
readonly class PagesResource extends AbstractResource
{
    /**
     * Resolves SEO meta and dynamic layout configuration for a static page by its slug.
     *
     * @param string $slug The page slug (e.g., 'about' or 'contact').
     * 
     * @return array<string, mixed> The page object containing content and metadata.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function getBySlug(string $slug): array
    {
        return $this->client->request('GET', "pages/{$slug}");
    }
}
