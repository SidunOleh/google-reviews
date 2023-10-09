<?php

namespace GoogleReviews\Models;

use Exception;

class Setting extends Model
{
    public function get(): array
    {
        $settings = get_option( 'google_reviews_settings' );

        return [
            'client_id' => $settings[ 'client_id' ] ?? '',
            'client_secret' => $settings[ 'client_secret' ] ?? '',
            'redirect_uri' => $settings[ 'redirect_uri' ] ?? '',
        ];
    }

    public function save( string $name, string $value ): void
    {
        $settings = get_option( 'google_reviews_settings' );
        $settings[ $name ] = $value;

        update_option( 'google_reviews_settings', $settings );
    }

    public function saveFromClientsSecretsFile( string $path ): void
    {
        if ( ! file_exists( $path ) ) {
            throw new Exception( 'Clients secrets file not found.' );
        }

        $settings = json_decode( file_get_contents( $path ), true );
        if (
            ! isset( $settings[ 'web' ][ 'client_id' ] ) or
            ! isset( $settings[ 'web' ][ 'client_secret' ] ) or
            ! isset( $settings[ 'web' ][ 'redirect_uris' ] )
        ) {
            throw new Exception( 'Invalid clients secrets file.' );
        }

        update_option( 'google_reviews_settings', [
            'client_id' => $settings[ 'web' ][ 'client_id' ],
            'client_secret' => $settings[ 'web' ][ 'client_secret' ],
            'redirect_uri' => $settings[ 'web' ][ 'redirect_uris' ][0],
        ] );
    }
}