<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;
use HeadlessBlog\Sdk\Exception\BadRequestException;
use HeadlessBlog\Sdk\Exception\HeadlessBlogException;
use HeadlessBlog\Sdk\Exception\RateLimitException;
use HeadlessBlog\Sdk\Exception\ServerException;
use HeadlessBlog\Sdk\Exception\UnauthorizedException;
use HeadlessBlog\Sdk\Resource\BootstrapResource;
use HeadlessBlog\Sdk\Resource\CategoriesResource;
use HeadlessBlog\Sdk\Resource\ContentTypesResource;
use HeadlessBlog\Sdk\Resource\HomeResource;
use HeadlessBlog\Sdk\Resource\NewsletterResource;
use HeadlessBlog\Sdk\Resource\PostsResource;
use HeadlessBlog\Sdk\Resource\PostTypesResource;
use HeadlessBlog\Sdk\Resource\SearchResource;
use HeadlessBlog\Sdk\Resource\SettingsResource;
use HeadlessBlog\Sdk\Resource\SitemapResource;
use HeadlessBlog\Sdk\Resource\TagsResource;
use HeadlessBlog\Sdk\Resource\TaxonomiesResource;

/**
 * The main SDK Client for the Headless.Blog API.
 * 
 * Provides access to all API resources through properly instantiated resource objects.
 */
readonly class Client
{
    /** @var string The current SDK version. */
    public const VERSION = '1.0.0';

    /** @var GuzzleClient The underlying HTTP client. */
    private GuzzleClient $httpClient;

    /** @var BootstrapResource Application Bootstrap & Navigation API. */
    public BootstrapResource $bootstrap;

    /** @var SettingsResource Website Settings API. */
    public SettingsResource $settings;

    /** @var HomeResource Consolidated Homepage Data API. */
    public HomeResource $home;

    /** @var SearchResource Hybrid Search API. */
    public SearchResource $search;

    /** @var PostsResource Posts Index, Filtering, Details, and Comments API. */
    public PostsResource $posts;

    /** @var CategoriesResource Categories Metadata API. */
    public CategoriesResource $categories;

    /** @var TagsResource Tags Metadata API. */
    public TagsResource $tags;

    /** @var TaxonomiesResource Taxonomies Metadata API. */
    public TaxonomiesResource $taxonomies;

    /** @var PostTypesResource Post Types API. */
    public PostTypesResource $postTypes;

    /** @var ContentTypesResource Static Content Types API. */
    public ContentTypesResource $contentTypes;

    /** @var NewsletterResource Newsletter Subscription API. */
    public NewsletterResource $newsletter;

    /** @var SitemapResource Sitemap Generation API. */
    public SitemapResource $sitemap;

    /**
     * Initializes the SDK Client.
     *
     * @param Config            $config     The configuration object containing API credentials.
     * @param GuzzleClient|null $httpClient An optional custom Guzzle HTTP client.
     */
    public function __construct(Config $config, ?GuzzleClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?? new GuzzleClient([
            'base_uri' => rtrim($config->baseUrl, '/') . '/',
            'timeout'  => $config->timeout,
            'headers'  => [
                'Authorization' => 'Bearer ' . $config->apiKey,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'User-Agent'    => 'HeadlessBlog-PHPSDK/' . self::VERSION,
            ],
        ]);

        $this->bootstrap = new BootstrapResource($this);
        $this->settings = new SettingsResource($this);
        $this->home = new HomeResource($this);
        $this->search = new SearchResource($this);
        $this->posts = new PostsResource($this);
        $this->categories = new CategoriesResource($this);
        $this->tags = new TagsResource($this);
        $this->taxonomies = new TaxonomiesResource($this);
        $this->postTypes = new PostTypesResource($this);
        $this->contentTypes = new ContentTypesResource($this);
        $this->newsletter = new NewsletterResource($this);
        $this->sitemap = new SitemapResource($this);
    }

    /**
     * Performs an HTTP request to the Headless.Blog API.
     *
     * @param string               $method  The HTTP method (GET, POST, etc.).
     * @param string               $uri     The API endpoint URI.
     * @param array<string, mixed> $options Additional Guzzle request options (e.g., query, json).
     * 
     * @return mixed The parsed JSON response, usually an associative array.
     * 
     * @throws BadRequestException   If the request payload or query fails validation (HTTP 400).
     * @throws UnauthorizedException If the API token is missing, invalid, or expired (HTTP 401/403).
     * @throws RateLimitException    If the API rate limit is exceeded (HTTP 429).
     * @throws ServerException       If a catastrophic server error occurs (HTTP 500).
     * @throws HeadlessBlogException If any other unexpected error occurs.
     */
    public function request(string $method, string $uri, array $options = []): mixed
    {
        try {
            $response = $this->httpClient->request($method, ltrim($uri, '/'), $options);
            $body = (string) $response->getBody();
            
            // Allow empty responses
            if ($body === '') {
                return null;
            }

            return json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (ClientException $e) {
            $this->handleClientException($e);
        } catch (GuzzleServerException $e) {
            throw new ServerException('A server error occurred.', $e->getResponse()->getStatusCode(), $e);
        } catch (\Throwable $e) {
            throw new HeadlessBlogException($e->getMessage(), $e->getCode() ?: 500, $e);
        }
    }

    /**
     * Handles 4xx HTTP client errors and maps them to SDK specific exceptions.
     *
     * @param ClientException $e The Guzzle client exception.
     * 
     * @return void
     * 
     * @throws HeadlessBlogException
     */
    private function handleClientException(ClientException $e): void
    {
        $response = $e->getResponse();
        $statusCode = $response->getStatusCode();
        
        $body = (string) $response->getBody();
        $data = json_decode($body, true);
        
        $message = $data['message'] ?? $e->getMessage();

        switch ($statusCode) {
            case 400:
                $details = $data['details'] ?? [];
                throw new BadRequestException($message, $details, 400, $e);
            case 401:
            case 403:
                throw new UnauthorizedException($message, $statusCode, $e);
            case 429:
                throw new RateLimitException($message, 429, $e);
            default:
                throw new HeadlessBlogException($message, $statusCode, $e);
        }
    }
}
