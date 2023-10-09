<?php

namespace GoogleReviews;

use GoogleReviews\Controllers\GoogleOAuth2Controller;
use GoogleReviews\Controllers\GoogleReviewsController;
use GoogleReviews\Controllers\SettingsController;
use GoogleReviews\Pages\MainPage;
use GoogleReviews\Shortcodes\GoogleReviewsShortcode;

class Core
{
    public function run()
    {
        $this->pages();
        $this->ajaxActions();
        $this->cronTasks();
        $this->shortcodes();
    }

    private function pages()
    {
        ( new MainPage )->create();
    }

    private function ajaxActions()
    {
        $googleOAuthController = new GoogleOAuth2Controller;
        ajax_action_callback( 'auth_redirect', [ $googleOAuthController, 'authRedirect' ] );
        ajax_action_callback( 'get_token', [ $googleOAuthController, 'getToken' ] );

        $googleReviewsController = new GoogleReviewsController;
        ajax_action_callback( 'download_reviews', [ $googleReviewsController, 'download' ] );
        ajax_action_callback( 'get_reviews', [ $googleReviewsController, 'get' ], true );
        ajax_action_callback( 'load_more_reviews', [ $googleReviewsController, 'load_more' ], true );

        $settingsController = new SettingsController;
        ajax_action_callback( 'get_settings', [ $settingsController, 'get' ] );
        ajax_action_callback( 'save_setting', [ $settingsController, 'save' ] );
        ajax_action_callback( 'upload_client_secrets', [ $settingsController, 'uploadClientSecretsFile' ] );
    }

    private function cronTasks()
    {
        add_action( 'google_reviews_refresh', [ new GoogleReviewsController, 'download' ] );
    }

    private function shortcodes()
    {
        ( new GoogleReviewsShortcode )->create();
    }
}