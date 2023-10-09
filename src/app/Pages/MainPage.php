<?php

namespace GoogleReviews\Pages;

class MainPage extends Page
{
    public function addPage()
    {
        add_menu_page(
            __( 'Google Reviews', 'google-reviews' ),
            __( 'Google Reviews', 'google-reviews' ),
            'manage_options',
            'google-reviews',
            [ $this, 'renderPage' ],
            plugin_dir_url( GOOGLE_REVIEWS_ROOT . '/google-reviews.php' ) . '/src/assets/admin/img/google-icon.png',
            65
        );
    }

    public function renderPage()
    {
        require_once GOOGLE_REVIEWS_ROOT . '/src/views/admin/main.php';
    }

    public function enqueueStyles()
    {
        if ( ! in_array( $_GET[ 'page' ] ?? '', [ 'google-reviews', ] ) ) return;

        wp_enqueue_style(
            'main',
            plugin_dir_url( GOOGLE_REVIEWS_ROOT . '/google-reviews.php' ) . '/src/assets/admin/css/style.css'
        );
    }

    public function enqueueScripts()
    {
        if ( ! in_array( $_GET[ 'page' ] ?? '', [ 'google-reviews', ] ) ) return;

        wp_enqueue_script( 
            'main', 
            plugin_dir_url( GOOGLE_REVIEWS_ROOT . '/google-reviews.php' ) . '/src/assets/admin/js/bundle.js',
            [],
            false,
            true
        );
    }
}