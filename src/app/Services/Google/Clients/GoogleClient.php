<?php

namespace GoogleReviews\Services\Google\Clients;

use Psr\Http\Message\ResponseInterface;

interface GoogleClient
{
    public function authRequest( string $method, string $url, array $options = [] ): ResponseInterface;
}