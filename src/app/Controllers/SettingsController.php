<?php

namespace GoogleReviews\Controllers;

use Exception;
use GoogleReviews\Models\Setting;

class SettingsController extends Controller
{
    private Setting $setting;

    public function __construct()
    {
        $this->setting = new Setting;
    }

    public function get()
    {
        $settings = $this->setting->get();

        $this->jsonResponse( [
            'settings' => $settings,
        ] );
    }

    public function save()
    {
        $name = $_POST[ 'name' ] ?? '';
        $value = $_POST[ 'value' ] ?? '';

        $this->setting->save( $name, $value );

        $this->jsonResponse( [
            'message' => 'OK',
        ] );
    }

    public function uploadClientSecretsFile()
    {
        try {
            $this->setting->saveFromClientsSecretsFile( 
                $_FILES[ 'client_secrets' ][ 'tmp_name' ] ?? ''
            );
        } catch ( Exception $e ) {
            $this->jsonResponse( [
                'error' => $e->getMessage(),
            ], 400 );
        }

        $this->jsonResponse( [
            'message' => 'OK',
        ] );
    }
}