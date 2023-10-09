<?php

namespace GoogleReviews\Controllers;

use Exception;
use GoogleReviews\Models\GoogleReview;

class GoogleReviewsController extends Controller
{
    private GoogleReview $googleReview;

    public function __construct()
    {
        $this->googleReview = new GoogleReview;
    }

    public function download()
    {
        try {
            $reviews = $this->googleReview->dowload();
            $this->googleReview->delete();
            $this->googleReview->save( $reviews );
        } catch ( Exception $e ) {
            $this->jsonResponse( [
                'error' => $e->getMessage(),
            ], 400 );
        }

        $this->jsonResponse( [
            'message' => 'OK',
        ] );
    }

    public function get()
    {
        $offset = $_GET[ 'offset' ] ?? 0;
        $limit = $_GET[ 'limit' ] ?? 10;
        $order = $_GET[ 'order' ] ?? 'created_at';
        $sort = $_GET[ 'sort' ] ?? 'desc';

        $reviews = $this->googleReview->get( $offset, $limit, $order, $sort );
        $total = $this->googleReview->total();
        $avgRating = $this->googleReview->avgRating();

        $this->jsonResponse( [
            'reviews' => $reviews,
            'total' => $total,
            'avgRating' => $avgRating,
        ] );
    }

    public function load_more()
    {
        $offset = $_GET[ 'offset' ] ?? 0;
        $limit = $_GET[ 'limit' ] ?? 10;

        $reviews = $this->googleReview->get( $offset, $limit, 'created_at', 'desc' );
        $total = $this->googleReview->total();

        $reviewsHtml = template_read(
            GOOGLE_REVIEWS_ROOT . '/src/views/public/reviews.php',
            [ 'reviews' => $reviews, ]
        );

        $this->jsonResponse( [
            'reviewsHtml' => $reviewsHtml,
            'isEnd' => ( $offset + $limit ) >= $total,
        ] );
    }
}