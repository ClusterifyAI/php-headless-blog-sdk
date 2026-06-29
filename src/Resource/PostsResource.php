<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Resource;

/**
 * Handles the Posts API endpoints, including listing, details, and comments.
 */
readonly class PostsResource extends AbstractResource
{
    /**
     * Serves paginated lists of blog posts.
     * 
     * Supports intersection filtering via query parameters.
     *
     * @param array<string, mixed> $filters Optional filters (page, limit, category, tag, taxonomyGroup, taxonomyTerm, postType).
     * 
     * @return array<string, mixed> The paginated posts result containing 'pagination' and 'posts' array.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function list(array $filters = []): array
    {
        return $this->client->request('GET', 'posts', [
            'query' => $filters,
        ]);
    }

    /**
     * Fetches comprehensive details for a single blog post by its UUID.
     *
     * @param string $id The UUID of the post.
     * 
     * @return array<string, mixed> The detailed post object.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function get(string $id): array
    {
        return $this->client->request('GET', "posts/{$id}");
    }

    /**
     * Fetches comprehensive details for a single blog post by its URL Slug.
     *
     * @param string $slug The URL slug of the post.
     * 
     * @return array<string, mixed> The detailed post object.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function getBySlug(string $slug): array
    {
        return $this->client->request('GET', "posts/slug/{$slug}");
    }

    /**
     * Retrieves all approved comments for a post.
     * 
     * Comments are automatically grouped into a deeply nested chronological thread.
     *
     * @param string $id The UUID of the post.
     * 
     * @return array<int, mixed> An array of nested comments.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function getComments(string $id): array
    {
        return $this->client->request('GET', "posts/{$id}/comments");
    }

    /**
     * Submits a new comment to a post.
     * 
     * Automatically assigned a 'pending' status for admin moderation.
     *
     * @param string               $id   The UUID of the post.
     * @param array<string, mixed> $data The comment payload (content, authorName, authorEmail, optional parentId).
     * 
     * @return mixed The created comment response or success status.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function addComment(string $id, array $data): mixed
    {
        return $this->client->request('POST', "posts/{$id}/comments", [
            'json' => $data,
        ]);
    }
}
