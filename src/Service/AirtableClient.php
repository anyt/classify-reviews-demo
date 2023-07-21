<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AirtableClient
{
    public function __construct(
        private HttpClientInterface $client,
        private string $apiKey,
        private string $base,
        private string $table
    ) {
    }

    public function fetchReviews()
    {
        $response = $this->client->request('GET', "https://api.airtable.com/v0/{$this->base}/{$this->table}", [
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}"
            ]
        ]);

        return $response->toArray();
    }
}
