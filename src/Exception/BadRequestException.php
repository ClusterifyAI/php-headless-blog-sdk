<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Exception;

/**
 * Thrown when the API returns a 400 Bad Request error.
 * 
 * Typically indicates that query parameters or body payloads failed validation.
 */
class BadRequestException extends HeadlessBlogException
{
    /** @var array<string, mixed> Specific validation error details provided by the API. */
    private array $details;

    /**
     * @param string     $message  The error message.
     * @param array      $details  Validation error details.
     * @param int        $code     The HTTP status code (defaults to 400).
     * @param \Throwable $previous The previous exception.
     */
    public function __construct(string $message, array $details = [], int $code = 400, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->details = $details;
    }

    /**
     * Retrieves the specific validation errors returned by the API.
     *
     * @return array<string, mixed>
     */
    public function getDetails(): array
    {
        return $this->details;
    }
}
