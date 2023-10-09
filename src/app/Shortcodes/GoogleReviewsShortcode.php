<?php

namespace GoogleReviews\Shortcodes;

use GoogleReviews\Models\GoogleReview;

class GoogleReviewsShortcode extends Shortcode
{
    protected string $name = 'google-reviews';

    public function render( $attr ): string
    {
        $googleReview = new GoogleReview;
        $reviews = $googleReview->get( 0, 10, 'created_at', 'desc' );
        $total = $googleReview->total();
        $avgRating = $googleReview->avgRating();
        
        $reviewsHtml = template_read(
            GOOGLE_REVIEWS_ROOT . '/src/views/public/google-reviews.php',
            [ 
                'attr' => $attr,
                'reviews' => $reviews, 
                'total' => $total,
                'avgRating' => $avgRating,
            ]
        );

        return $reviewsHtml;
    }
}