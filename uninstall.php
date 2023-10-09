<?php

defined( 'WP_UNINSTALL_PLUGIN' ) or die;

/**
 * Delete tables
 */
global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->base_prefix}google_reviews" );

/**
 * Delete options
 */
delete_option( 'google_reviews_settings' );
delete_option( 'google_reviews_token' );