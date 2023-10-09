<?php

/**
 * Register callback for ajax action
 */
function ajax_action_callback( 
    string $action, 
    callable $callback, 
    bool $nopriv = false 
) {
    add_action( "wp_ajax_{$action}", $callback );

    if ( $nopriv ) {
        add_action( "wp_ajax_nopriv_{$action}", $callback ); 
    }
}

/**
 * Read template
 */
function template_read( $template_path, $data = [] ): string {
    extract( $data );

    ob_start();
    require $template_path;
    $template_data = ob_get_clean();

    return $template_data;
}

/**
 * Format date in ago time
 */
function time_elapsed_string( string $datetime, bool $full = false ): string {
    $now = new DateTime;
    $ago = new DateTime( $datetime );
    $diff = $now->diff( $ago );

    $diff->w = floor( $diff->d / 7 );
    $diff->d -= $diff->w * 7;

    $string = [
        'y' => __( 'year', 'google-reviews' ),
        'm' => __( 'month', 'google-reviews' ),
        'w' => __( 'week', 'google-reviews' ),
        'd' => __( 'day', 'google-reviews' ),
        'h' => __( 'hour', 'google-reviews' ),
        'i' => __( 'minute', 'google-reviews' ),
        's' => __( 'second', 'google-reviews' ),
    ];
    foreach ( $string as $k => &$v ) {
        if ( $diff->$k ) {
            $v = $diff->$k . ' ' . $v . ( $diff->$k > 1 ? 's' : '' );
        } else {
            unset( $string[ $k ] );
        }
    }

    if ( ! $full ) $string = array_slice( $string, 0, 1 );
    
    return $string ? implode( ', ', $string ) . __( ' ago', 'google-reviews' ) : __( 'just now', 'google-reviews' );
}