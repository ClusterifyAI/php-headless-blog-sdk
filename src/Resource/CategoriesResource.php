<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Resource;

/**
 * Handles the Categories API endpoints.
 */
readonly class CategoriesResource extends AbstractResource
{
    /**
     * Retrieves all categories.
     *
     * @return array<int, mixed> An array of category objects.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function list(): array
    {
        return $this->client->request('GET', 'categories');
    }

    /**
     * Retrieves a specific category by its slug.
     *
     * @param string $slug The category slug.
     * 
     * @return array<string, mixed> The category object.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function getBySlug(string $slug): array
    {
        return $this->client->request('GET', "categories/{$slug}");
    }
}
