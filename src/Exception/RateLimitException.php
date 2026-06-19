<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Exception;

/**
 * Thrown when the API returns a 429 Too Many Requests error.
 * 
 * Indicates that the client IP has exceeded the configured sliding-window rate limit.
 */
class RateLimitException extends HeadlessBlogException
{
}
