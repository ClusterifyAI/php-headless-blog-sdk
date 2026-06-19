<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Resource;

/**
 * Handles the Content Types API endpoints.
 */
readonly class ContentTypesResource extends AbstractResource
{
    /**
     * Retrieves static content types defined by the system (e.g., markdown, rich-text).
     *
     * @return array<int, mixed> An array of content type objects.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function list(): array
    {
        return $this->client->request('GET', 'content-types');
    }
}
