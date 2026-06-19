<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Resource;

/**
 * Handles the Hybrid Search API endpoints.
 */
readonly class SearchResource extends AbstractResource
{
    /**
     * Executes high-speed text search across published posts.
     * 
     * Intelligently chooses between next-generation Semantic Vector Search and Classic Keyword Search
     * based on dashboard settings.
     *
     * @param string $query The search string.
     * 
     * @return array<int, mixed> An array of matching posts.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function query(string $query): array
    {
        return $this->client->request('GET', 'search', [
            'query' => [
                'q' => $query,
            ],
        ]);
    }
}
