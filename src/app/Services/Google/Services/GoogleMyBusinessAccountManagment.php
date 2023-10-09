<?php

namespace GoogleReviews\Services\Google\Services;

use Exception;

class GoogleMyBusinessAccountManagment extends GoogleService
{
    public function getAccounts(): array
    {
        $response = $this->googleClient->authRequest(
            'GET',
            'https://mybusinessaccountmanagement.googleapis.com/v1/accounts',
        );
        if ( $response->getStatusCode() != 200 ) {
            throw new Exception( $response->getReasonPhrase(), $response->getStatusCode() );
        }

        $content = json_decode( $response->getBody()->getContents(), true );

        return $content[ 'accounts' ];
    }
}