<?php

// src/Controller/ReviewController.php

namespace App\Controller;

use App\Service\AirtableClient;
use App\Service\OpenAIClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController {

    #[Route('/', name: 'app_reviews')]
    public function index(AirtableClient $airtableClient, OpenAIClient $openAIClient) {
        $reviews = $airtableClient->fetchReviews();
        $scoredReviews = [];
        foreach ($reviews['records'] as $review) {
            $score = $openAIClient->scoreReview($review['fields']['review']); // Assuming the field name is 'Review'
            $scoredReviews[] = $score;
        }
        return $this->render('reviews/index.html.twig', [
            'scoredReviews' => $scoredReviews
        ]);
    }
}
