# Headless Blog PHP SDK (v1.0.0)

Welcome to the official Headless.Blog PHP Developer Guide. This SDK provides a high-performance, strictly typed, and completely database-agnostic interface for PHP applications, Laravel frameworks, decoupled monoliths, or any server-side integrations to consume blog content.

By utilizing this SDK, your backend or frontend application acts purely as a headless consumer. All complex business logic, multi-tenant security, taxonomy grouping, vector searches, and data aggregation are handled securely by the Headless.Blog backend.

---

## Installation

Install the package via Composer. The SDK requires **PHP 8.2+** and uses Guzzle for robust HTTP requests.

```bash
composer require headlessblog/sdk
```

---

## Initialization & Core Architecture

### 1. Strict Token Authentication

The Headless API relies on strict API token authentication to isolate multi-tenant data. The SDK automatically manages this for you via the `Config` object.

```php
use HeadlessBlog\Sdk\Config;
use HeadlessBlog\Sdk\Client;

// Initialize the Configuration with your active API key from the SaaS Dashboard
$config = new Config(
    apiKey: 'your_api_token_here',
    baseUrl: 'https://api.headless.blog/v1', // Optional, defaults to this URL
    timeout: 30                              // Optional, timeout in seconds
);

// Instantiate the SDK Client
$client = new Client($config);
```

### 2. High-Speed Hybrid Caching & CDN

- **Image CDN Mapping**: The backend API automatically intercepts all responses and replaces relative database paths with fully qualified CDN URLs, providing automatic WebP compression.
- **Caching**: The backend utilizes Redis and Edge Delivery caching to ensure high performance.

---

## Global Error Handling & Exceptions

All API errors throw specific Exceptions extending `HeadlessBlog\Sdk\Exception\HeadlessBlogException`. You should design your application to catch these gracefully.

### `BadRequestException` (HTTP 400)
Thrown when query parameters or body payloads fail validation.
```php
try {
    $client->posts->list(['limit' => 500]); // Exceeds max limit
} catch (\HeadlessBlog\Sdk\Exception\BadRequestException $e) {
    echo $e->getMessage();
    print_r($e->getDetails()); // Returns specific validation failures
}
```

### `UnauthorizedException` (HTTP 401 / 403)
Thrown when the API token is completely missing, invalid, expired, or belongs to a disabled tenant.

### `RateLimitException` (HTTP 429)
Thrown when your client IP exceeds the configured sliding-window rate limit.

### `ServerException` (HTTP 500+)
Thrown on catastrophic database or cache connection failures from the Headless.Blog servers.

---

## Endpoint Reference Guide

All endpoints return parsed associative arrays. Below you will find the method signatures, parameters, and the exact response schemas.

### 1. Application Bootstrap & Navigation

Delivers all foundational categories, tags, and multi-dimensional taxonomies. Applications should call this once on launch or cache the result locally to build navigation menus.

**Method:**
```php
$bootstrapData = $client->bootstrap->get();
```

**Response Schema:**
```php
[
    'categories' => [
        [
            'id' => 1, 
            'parentId' => null,
            'name' => 'Technology', 
            'slug' => 'technology', 
            'url_link' => 'https://...',
            'description' => 'Tech news',
            'imagePath' => 'https://cdn...',
            'position' => 0 
        ]
    ],
    'tags' => [
        ['id' => 'uuid', 'name' => 'TypeScript', 'slug' => 'typescript', 'url_link' => 'https://...']
    ],
    'taxonomies' => [
        [
            'id' => 'uuid',
            'name' => 'Difficulty',
            'slug' => 'difficulty',
            'selectionType' => 'single',
            'isNavigation' => true,
            'visibleInPostTypeIds' => [1, 2],
            'terms' => [
                ['id' => 'uuid', 'name' => 'Easy', 'slug' => 'easy', 'colorCode' => '#00FF00']
            ]
        ]
    ],
    'postTypes' => [
        ['id' => 1, 'name' => 'Default', 'position' => 0, 'isDefault' => true, 'isDeletable' => false]
    ]
]
```

---

### 2. Website Settings

Delivers general website settings. Call this to retrieve the base configuration like the website name, URLs, and AI feature toggles.

**Method:**
```php
$settings = $client->settings->get();
```

**Response Schema:**
```php
[
    'id' => 'uuid',
    'websiteName' => 'My Blog',
    'websiteBaseUrl' => 'https://myblog.com',
    'blogLandingPageUrl' => 'blog',
    'metaSeoKeywords' => 'blog, kitchen, travel',
    'highlightedCategoryId' => 5,
    'semanticSearchHeadlessApi' => true,
    'semanticSearchWebsite' => true
]
```

---

### 3. Consolidated Homepage Data

Aggregates all homepage sections to build a full landing page in a single request, preventing UI waterfall loading.

**Method:**
```php
$homeData = $client->home->get();
```

**Response Schema:**
```php
[
    'section-hero'     => [ /* MobilePostSnippet array */ ],
    'section-recent'   => [ /* Array of 5 MobilePostSnippets */ ],
    'section-featured' => [ /* Array of 6 MobilePostSnippets */ ],
    'section-favorite' => [ /* Array of Curated Highlight MobilePostSnippets */ ]
]
```

---

### 4. Hybrid Search API

Executes high-speed text search across published posts. Intelligently chooses between next-generation Semantic Vector Search and Classic Keyword Search based on dashboard settings.

**Method:**
```php
// @param string $query Required search string
$results = $client->search->query('How to Bake Bread');
```

**Response Schema:**
```php
[
    [
        'id' => 'uuid',
        'title' => 'How to Bake Bread',
        'slug' => 'how-to-bake-bread',
        'url_link' => 'https://...',
        'excerpt' => "A beginner's guide to baking.",
        'featuredImagePathThumbnail' => 'https://cdn.headless.blog/_next/image?url=...',
        'featuredImageDirectUrl' => 'https://cdn.headless.blog/...',
        'postTypeId' => 1,
        'contentType' => 'markdown',
        'categories' => [['id' => 1, 'name' => 'Recipes', 'slug' => 'recipes', 'url_link' => 'https://...']],
        'tags' => [['id' => 'uuid', 'name' => 'Baking', 'slug' => 'baking', 'url_link' => 'https://...']],
        'taxonomies' => [ /* Mapped Taxonomy Terms */ ]
    ]
]
```

---

### 5. Posts Index & Filtering

Serves paginated lists of blog posts. Supports intersection filtering via an array of parameters.

**Method:**
```php
// @param array $filters Optional key-value pairs for filtering
$postsData = $client->posts->list([
    'page' => 1,                  // optional, default: 1
    'limit' => 12,                // optional, default: 12, max: 50
    'category' => 'recipes',      // optional, category slug
    'tag' => 'baking',            // optional, tag slug
    'taxonomyGroup' => 'level',   // optional, requires taxonomyTerm
    'taxonomyTerm' => 'easy'      // optional, requires taxonomyGroup
]);
```

**Response Schema:**
```php
[
    'pagination' => [
        'totalItems' => 42, 
        'totalPages' => 4, 
        'currentPage' => 1, 
        'limit' => 12
    ],
    'posts' => [
        [
            'id' => 'uuid',
            'title' => 'Understanding Headless Architecture',
            'slug' => 'understanding-headless',
            'url_link' => 'https://...',
            'excerpt' => 'A brief summary.',
            'featuredImagePath' => 'https://cdn.headless.blog/...',
            'featuredImagePathThumbnail' => 'https://cdn.headless.blog/...',
            'featuredImageDirectUrl' => 'https://cdn.headless.blog/...',
            'featuredImageAlt' => 'Image Alt Text',
            'publishedAt' => '2026-06-08T12:00:00Z',
            'postTypeId' => 1,
            'contentType' => 'markdown',
            'categories' => [ /* MobileCategorySnippets */ ],
            'tags' => [ /* MobileTagSnippets */ ],
            'taxonomies' => [ /* Mapped Taxonomy Terms */ ],
            'author' => [
                'id' => 'uuid', 
                'username' => 'admin', 
                'displayName' => 'Admin User', 
                'avatarUrl' => 'https://cdn.headless.blog/...' 
            ]
        ]
    ]
]
```

---

### 6. Post Details

Fetches comprehensive details for a single blog post. Includes parsed HTML content, authors, SEO meta, and structured FAQ arrays.

**Methods:**
```php
// @param string $id Lookup by post UUID
$post = $client->posts->get('uuid-of-post');

// @param string $slug Lookup by URL slug
$post = $client->posts->getBySlug('understanding-headless');
```

**Response Schema:**
```php
[
    'id' => 'uuid',
    'title' => 'Understanding Headless Architecture',
    'slug' => 'understanding-headless',
    'url_link' => 'https://...',
    'content' => '<p>HTML content here with <img src="https://cdn..."></p>',
    'excerpt' => 'A brief summary.',
    'featuredImagePath' => 'https://cdn.headless.blog/...',
    'featuredImagePathThumbnail' => 'https://cdn.headless.blog/...',
    'featuredImageDirectUrl' => 'https://cdn.headless.blog/...',
    'featuredImageAlt' => 'Image alt text',
    'metaSeoTitle' => 'SEO Title',
    'metaSeoDescription' => 'SEO Description',
    'contentFaq' => [
        ['question' => 'What is headless?', 'answer' => 'It is decoupled.']
    ],
    'metaSeoKeywords' => 'headless, architecture',
    'postTypeId' => 1,
    'contentType' => 'markdown',
    'categories' => [ /* Array of categories */ ],
    'tags' => [ /* Array of tags */ ],
    'taxonomies' => [ /* Filtered array of mapped Taxonomy Terms */ ],
    'author' => [ /* MobileAuthorSnippet */ ]
]
```

---

### 7. Comments Engine

Retrieves or submits comments for a specific post.

**Retrieve Comments:**
```php
// @param string $id Post UUID
$comments = $client->posts->getComments('uuid-of-post');

// Returns an array of nested comment objects
```

**Submit a Comment:**
Automatically assigned `pending` status for admin moderation.
```php
// @param string $id Post UUID
// @param array  $data Comment Payload
$response = $client->posts->addComment('uuid-of-post', [
    'content'     => 'Great post! Thanks for sharing.',
    'authorName'  => 'Jane Doe',
    'authorEmail' => 'jane@example.com',
    'parentId'    => 'optional-uuid-to-reply-to-comment'
]);
```

---

### 8. Taxonomies & Metadata

The API exposes dedicated resources for querying precise metadata objects.

**Categories & Tags:**
```php
$categories = $client->categories->list();
$category   = $client->categories->getBySlug('technology');

$tags = $client->tags->list();
$tag  = $client->tags->getBySlug('typescript');
```

**Taxonomies:**
```php
$taxonomies = $client->taxonomies->list();
$group      = $client->taxonomies->getByGroupSlug('difficulty');
$term       = $client->taxonomies->getByTermSlug('difficulty', 'easy');

// Taxonomy Group Response Schema:
// [
//     'id' => 'uuid',
//     'name' => 'Difficulty',
//     'slug' => 'difficulty',
//     'selectionType' => 'single',
//     'isNavigation' => true,
//     'visibleInPostTypeIds' => [1, 2],
//     'terms' => [
//         ['id' => 'uuid', 'name' => 'Easy', 'slug' => 'easy', 'colorCode' => '#00FF00']
//     ]
// ]
```

**Post Types & Content Types:**
```php
$postTypes = $client->postTypes->list();
// Returns: [['id' => 1, 'name' => 'Default', 'isDeletable' => false, 'isDefault' => true, 'position' => 0]]

$contentTypes = $client->contentTypes->list();
// Returns: [['id' => 'markdown', 'name' => 'Markdown'], ['id' => 'rich-text', 'name' => 'Rich Text']]
```

---

### 9. Static Pages

Resolves SEO meta and dynamic layout configuration for static segments like `/about` or `/contact`.

**Method:**
```php
// @param string $slug Page URL slug
$page = $client->pages->getBySlug('about');
```

**Response Schema:**
```php
[
    'id' => 'uuid',
    'title' => 'About Us',
    'slug' => 'about',
    'content' => '<p>Page content...</p>',
    'metaSeoTitle' => 'About Us | Headless.Blog',
    'metaSeoDescription' => 'Learn more about us.'
]
```

---

### 10. Newsletter

Registers a subscriber to the tenant's mailing list.

**Method:**
```php
// @param string $email Required email address
// @param string|null $name Optional name
$response = $client->newsletter->subscribe('user@example.com', 'Jane Doe');
```

---

### 11. Sitemap Generation

Dynamically generates standard sitemap data (URLs, last modification dates, priorities). Used to programmatically render dynamic `sitemap.xml` feeds.

**Method:**
```php
$sitemapData = $client->sitemap->list();
```

**Response Schema:**
```php
[
    [
        'url' => 'https://{your domain}/recipes/how-to-bake-bread',
        'lastModified' => '2026-06-08T12:00:00Z',
        'changeFrequency' => 'weekly',
        'priority' => 0.8
    ]
]
```
