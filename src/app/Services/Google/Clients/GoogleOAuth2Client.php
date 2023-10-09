<?php

namespace GoogleReviews\Services\Google\Clients;

use Exception;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class GoogleOAuth2Client implements GoogleClient
{
    private string $clientId;

    private string $clientSecret;

    private string $redirectUri;

    private ClientInterface $client;

    private array $scopes;

    private string $accessType;

    private string $responseType;

    private string $prompt;

    private array $token;

    public function __construct( 
        string $clientId, 
        string $clientSecret, 
        string $redirectUri,
        ClientInterface $client,
    ) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->client = $client;
        $this->scopes = [];
        $this->accessType = 'offline';
        $this->responseType = 'code';
        $this->prompt = 'consent';
        $this->token = [];
    }

    public function setScope( string $scope ): self
    {
        if ( ! in_array( $scope, $this->scopes ) ) {
            $this->scopes[] = $scope;
        }

        return $this;
    }

    public function setAccessType( string $accessType ): self
    {
        $this->accessType = $accessType;

        return $this;
    }

    public function setResponseType( string $responseType ): self
    {
        $this->responseType = $responseType;

        return $this;
    }

    public function setPrompt( string $prompt ): self
    {
        $this->prompt = $prompt;

        return $this;
    }

    public function getAuthUrl(): string
    {
        $queryParams = http_build_query( [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'scope' => implode( ',', $this->scopes ),
            'access_type' => $this->accessType,
            'response_type' => $this->responseType,
            'prompt' => $this->prompt,
        ] );

        return "https://accounts.google.com/o/oauth2/auth?{$queryParams}";
    }

    public function getToken( string $code ): array
    {
        $url = 'https://oauth2.googleapis.com/token';
        $data = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code',
            'code' => $code,
        ];
        $response = $this->client->request( 'POST', $url, [ 'form_params' => $data ] );

        if ( $response->getStatusCode() != 200 ) {
            throw new Exception( $response->getReasonPhrase(), $response->getStatusCode() );
        }
        
        $this->token = json_decode( $response->getBody()->getContents(), true );
        $this->token[ 'expires_in' ] += time();

        return $this->token;
    }

    public function setToken( array $token ): self
    {   
        $this->token = $token;

        return $this;
    }

    public function isTokenExpired(): bool
    {
        if ( 
            ! isset( $this->token[ 'expires_in' ] ) or 
            $this->token[ 'expires_in' ] < time() 
        ) {
            return true;
        }

        return false;
    }

    public function refreshToken(): array
    {
        if ( ! isset( $this->token[ 'refresh_token' ] ) ) {
            throw new Exception( 'Refresh token not found. Connect to Google.' );
        }

        $url = 'https://oauth2.googleapis.com/token';
        $data = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->token[ 'refresh_token' ],
        ];
        $response = $this->client->request( 'POST', $url, [ 'form_params' => $data ] );

        if ( $response->getStatusCode() != 200 ) {
            throw new Exception( $response->getReasonPhrase(), $response->getStatusCode() );
        }
        
        $this->token = json_decode( $response->getBody()->getContents(), true );
        $this->token[ 'expires_in' ] += time();
        $this->token[ 'refresh_token' ] = $data[ 'refresh_token' ];

        return $this->token;
    }

    public function authRequest( string $method, string $url, array $options = [] ): ResponseInterface
    {
        if ( $this->isTokenExpired() ) {
            $this->token = $this->refreshToken();
        }

        $options[ 'headers' ][ 'Authorization' ] = "Bearer {$this->token['access_token']}";

        $response = $this->client->request( $method, $url, $options );

        return $response;
    }
}
