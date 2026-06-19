<?php

declare(strict_types=1);

namespace HeadlessBlog\Sdk\Resource;

/**
 * Handles the Newsletter Subscription API endpoints.
 */
readonly class NewsletterResource extends AbstractResource
{
    /**
     * Registers a subscriber to the tenant's mailing list.
     *
     * @param string      $email The subscriber's email address.
     * @param string|null $name  The optional subscriber's name.
     * 
     * @return mixed The success response from the server.
     * @throws \HeadlessBlog\Sdk\Exception\HeadlessBlogException
     */
    public function subscribe(string $email, ?string $name = null): mixed
    {
        $data = ['email' => $email];
        if ($name !== null) {
            $data['name'] = $name;
        }

        return $this->client->request('POST', 'newsletter/subscribe', [
            'json' => $data,
        ]);
    }
}
