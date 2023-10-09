<?php

/**
 * Plugin Name: Google My Business Reviews
 * Description: Fetch google reviews via My Business API
 * Author: Sidun Oleh
 */

defined( 'ABSPATH' ) or die;

/**
 * Plugin root
 */
const GOOGLE_REVIEWS_ROOT = __DIR__;

/**
 * Composer autoloader
 */
require_once GOOGLE_REVIEWS_ROOT . '/vendor/autoload.php';

/**
 * Activate plugin
 */
$activator = new \GoogleReviews\Activator;
register_activation_hook( 
    __FILE__,
    [ $activator, 'activate' ]
);
add_action(
    'activated_plugin',
    [ $activator, 'activated' ]
);

/**
 * Deactivate plugin
 */
register_deactivation_hook( 
    __FILE__,
    [ new \GoogleReviews\Deactivator, 'deactivate' ]
);

/**
 * Run plugin
 */
( new \GoogleReviews\Core )->run();