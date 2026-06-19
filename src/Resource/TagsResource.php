<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Resource;

/**
 * Handles the Tags API endpoints.
 */
readonly class TagsResource extends AbstractResource
{
    /**
     * Retrieves all tags.
     *
     * @return array<int, mixed> An array of tag objects.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function list(): array
    {
        return $this->client->request('GET', 'tags');
    }

    /**
     * Retrieves a specific tag by its slug.
     *
     * @param string $slug The tag slug.
     * 
     * @return array<string, mixed> The tag object.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function getBySlug(string $slug): array
    {
        return $this->client->request('GET', "tags/{$slug}");
    }
}
