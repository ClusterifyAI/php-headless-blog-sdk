<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Resource;

/**
 * Handles the Taxonomies API endpoints.
 */
readonly class TaxonomiesResource extends AbstractResource
{
    /**
     * Retrieves all taxonomies.
     *
     * @return array<int, mixed> An array of taxonomy group objects.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function list(): array
    {
        return $this->client->request('GET', 'taxonomies');
    }

    /**
     * Retrieves a specific taxonomy group by its slug.
     *
     * @param string $groupSlug The taxonomy group slug.
     * 
     * @return array<string, mixed> The taxonomy group object.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function getByGroupSlug(string $groupSlug): array
    {
        return $this->client->request('GET', "taxonomies/{$groupSlug}");
    }

    /**
     * Retrieves a specific taxonomy term by its group slug and term slug.
     *
     * @param string $groupSlug The taxonomy group slug.
     * @param string $termSlug  The specific term slug.
     * 
     * @return array<string, mixed> The taxonomy term object.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function getByTermSlug(string $groupSlug, string $termSlug): array
    {
        return $this->client->request('GET', "taxonomies/{$groupSlug}/{$termSlug}");
    }
}
