<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Resource;

/**
 * Handles the Post Types API endpoints.
 */
readonly class PostTypesResource extends AbstractResource
{
    /**
     * Retrieves all post types configured for the active website.
     *
     * @return array<int, mixed> An array of post type objects.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function list(): array
    {
        return $this->client->request('GET', 'post-types');
    }
}
