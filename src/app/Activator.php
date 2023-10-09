<?php

namespace GoogleReviews;

class Activator
{
    public function activate()
    {
        $this->createGoogleReviewsTable();
        $this->registerCronHooks();
    }

    private function createGoogleReviewsTable()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $base_prefix = $wpdb->base_prefix;

        $sql = "CREATE TABLE IF NOT EXISTS {$base_prefix}google_reviews (
            id               BIGINT(20)    UNSIGNED NOT NULL  AUTO_INCREMENT,
            review_id        INT                    NOT NULL,
            author_name      VARCHAR(100)           NOT NULL,
            author_photo_url VARCHAR(100)           NOT NULL,
            rating           VARCHAR(100)           NOT NULL,
            comment          TEXT                   NOT NULL,
            created_at       DATETIME               NOT NULL,
            PRIMARY KEY(id)
        ) {$charset_collate}";
        
        require_once ABSPATH . '/wp-admin/includes/upgrade.php';
        dbDelta( $sql );

        if ( $wpdb->last_error ) die( $wpdb->last_error );
    }

    private function registerCronHooks()
    {
        wp_schedule_event( time(), 'daily', 'google_reviews_refresh' );
    }

    public function activated( $plugin )
    {
        if ( $plugin == 'google-reviews/google-reviews.php' ) {
            wp_redirect( admin_url( 'admin.php?page=google-reviews' ) );
            die;
        } 
    }
}