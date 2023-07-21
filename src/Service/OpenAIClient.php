<?php

namespace App\Service;

use GuzzleHttp\Client;

class OpenAIClient
{
    public function __construct(private Client $client, private string $apiKey)
    {
    }

    public function scoreReview($review)
    {
        // clean up $review from ###
        $review = str_replace('###', '', $review);

        $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Score and categorize the following review in the ###. " .
                            "Return the response in Json format with score and categories columns. " .
                            "Possible categories are: good, bad, neutral, and spam; " .
                            "score is from 0 to 10, where 0 is spam, 1 is bad, 10 is good"
                    ],
                    ['role' => 'user', 'content' => '###' . $review . '###']
                ]
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        $response = json_decode($data['choices'][0]['message']['content'], true);
        $response['message'] = $review;
        return $response;
    }
}
