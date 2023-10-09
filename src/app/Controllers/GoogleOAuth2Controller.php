<?php

namespace GoogleReviews\Controllers;

use Exception;
use GoogleReviews\Models\Setting;
use GoogleReviews\Services\Google\Clients\GoogleOAuth2Client;
use GuzzleHttp\Client;

class GoogleOAuth2Controller extends Controller
{
    private GoogleOAuth2Client $googleOAuth2Client;

    public function __construct()
    {
        $settings = ( new Setting )->get();
        $this->googleOAuth2Client = new GoogleOAuth2Client( 
            $settings[ 'client_id' ],
            $settings[ 'client_secret' ],
            $settings[ 'redirect_uri' ],
            new Client()
        );
    }

    public function authRedirect()
    {
        $this->googleOAuth2Client->setScope( 
            'https://www.googleapis.com/auth/business.manage' 
        );

        $this->jsonResponse( [
            'redirectUrl' => $this->googleOAuth2Client->getAuthUrl(),
        ] );
    }

    public function getToken()
    {
        $code = $_GET[ 'code' ] ?? '';

        try {
            $token = $this->googleOAuth2Client->getToken( $code );
        } catch ( Exception $e ) {
            $this->redirect( admin_url( 'admin.php?page=google-reviews&connected=false' ) );
        }

        update_option( 'google_reviews_token', $token );

        $this->redirect( admin_url( 'admin.php?page=google-reviews&connected=true' ) );
    }
}