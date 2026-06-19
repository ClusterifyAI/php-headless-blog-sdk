<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Exception;

/**
 * Thrown when the API returns a 401 or 403 error.
 * 
 * Indicates that the API token is completely missing from the request headers,
 * is invalid, expired, or belongs to a disabled tenant.
 */
class UnauthorizedException extends HeadlessBlogException
{
}
