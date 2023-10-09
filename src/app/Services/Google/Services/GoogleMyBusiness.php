<?php

namespace GoogleReviews\Services\Google\Services;

use Exception;

class GoogleMyBusiness extends GoogleService
{
    public function getReviews( string $accountId, string $locationId ): array
    {
        $reviews = [];
        $nextPageToken = null;
        while ( true ) {
            $response = $this->getReviewsPage( $accountId, $locationId, $nextPageToken );
            array_merge( $reviews, $response[ 'reviews' ] );
            if ( isset( $response[ 'nextPageToken' ] ) ) {
                $nextPageToken = $response[ 'nextPageToken' ];
            } else {
                break;
            }
        }

        return $reviews;
    }

    private function getReviewsPage( string $accountId, string $locationId, string|null $nextPageToken = null ): array
    {
        $queryParams[ 'pageSize' ] = 50;
        if ( $nextPageToken ) $queryParams[ 'pageToken' ] = $nextPageToken;
        $response = $this->googleClient->authRequest(
            'GET',
            "https://mybusiness.googleapis.com/v4/accounts/{$accountId}/locations/{$locationId}/reviews", [
                'query' => $queryParams,
            ],
        );
        if ( $response->getStatusCode() != 200 ) {
            throw new Exception( $response->getReasonPhrase(), $response->getStatusCode() );
        }

        $content = json_decode( $response->getBody()->getContents(), true );

        return $content;
    }
}