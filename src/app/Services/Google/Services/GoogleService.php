<?php

namespace GoogleReviews\Services\Google\Services;

use GoogleReviews\Services\Google\Clients\GoogleClient;

abstract class GoogleService
{
    protected GoogleClient $googleClient;

    public function __construct( GoogleClient $googleClient )
    {
        $this->googleClient = $googleClient;
    }
}