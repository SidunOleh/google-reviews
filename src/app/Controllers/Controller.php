<?php

namespace GoogleReviews\Controllers;

abstract class Controller
{
    protected function jsonResponse( array $data, $code = 200 )
    {
        wp_send_json( $data, $code );
        die;
    }

    protected function redirect( $url, $code = 301 )
    {
        wp_redirect( $url, $code );
        die;
    }
}