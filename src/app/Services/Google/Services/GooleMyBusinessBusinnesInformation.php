<?php

namespace GoogleReviews\Services\Google\Services;

use Exception;

class GooleMyBusinessBusinnesInformation extends GoogleService
{
    public function getLocations( string $accountId ): array
    {
        $response = $this->googleClient->authRequest(
            'GET',
            "https://mybusinessbusinessinformation.googleapis.com/v1/accounts/{$accountId}/locations", [
                'query' => [
                    'read_mask' => 'name',
                ],
            ],
        );
        if ( $response->getStatusCode() != 200 ) {
            throw new Exception( $response->getReasonPhrase(), $response->getStatusCode() );
        }

        $content = json_decode( $response->getBody()->getContents(), true );

        return $content[ 'locations' ];
    }
}