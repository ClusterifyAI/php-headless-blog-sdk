<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Exception;

/**
 * Thrown when the API returns a 500 Internal Server Error.
 * 
 * Indicates a catastrophic database or cache connection failure on the server side.
 */
class ServerException extends HeadlessBlogException
{
}
