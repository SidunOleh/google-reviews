<?php

namespace GoogleReviews\Models;

use DateTime;
use GoogleReviews\Services\Google\Clients\GoogleOAuth2Client;
use GoogleReviews\Services\Google\Services\GoogleMyBusiness;
use GoogleReviews\Services\Google\Services\GoogleMyBusinessAccountManagment;
use GoogleReviews\Services\Google\Services\GooleMyBusinessBusinnesInformation;
use GuzzleHttp\Client;

class GoogleReview extends Model
{   
    private string $table;

    private GoogleOAuth2Client $googleOAuth2Client;

    public function __construct()
    {
        parent::__construct();

        $this->table = "{$this->db->base_prefix}google_reviews";

        $settings = ( new Setting )->get();
        $this->googleOAuth2Client = new GoogleOAuth2Client(
            $settings[ 'client_id' ],
            $settings[ 'client_secret' ],
            $settings[ 'redirect_uri' ],
            new Client()
        );
        $this->googleOAuth2Client->setToken( 
            get_option( 'google_reviews_token' ) 
        );
    }

    public function dowload(): array
    {
        if (
            ! $accountId = $this->getAccountId() or
            ! $locationId = $this->getLocationId( $accountId )
        ) {
            return [];
        }

        $reviews = ( new GoogleMyBusiness( $this->googleOAuth2Client ) )
                ->getReviews( $accountId, $locationId );

        return $reviews;
    }

    private function getAccountId(): string|null
    {
        $accounts = ( new GoogleMyBusinessAccountManagment( $this->googleOAuth2Client  ) )
            ->getAccounts();
        
        if ( ! $accounts ) return null;

        $accountId = explode( '/', $accounts[0][ 'name' ] )[1];

        return $accountId;
    }

    private function getLocationId( string $accountId ): string|null
    {
        $locations = ( new GooleMyBusinessBusinnesInformation( $this->googleOAuth2Client  ) )
            ->getLocations( $accountId );

        if ( ! $locations ) return null;

        $locationId = explode( '/', $locations[0][ 'name' ] )[1];
        
        return $locationId;
    }

    public function save( array $reviews ): void
    {
        $values = '';
        foreach ($reviews as $review) {
            $rating = $this->ratingNumber( $review['starRating'] );
            $createdAt = ( new DateTime( $review['createTime'] ) )
                ->format( 'Y-m-d h:i:s' );
            $values .= "(
                '{$review['reviewId']}', 
                '{$review['reviewer']['displayName']}', 
                '{$review['reviewer']['profilePhotoUrl']}', 
                '{$rating}', 
                '" . $this->db->_real_escape( $review['comment'] ) . "', 
                '{$createdAt}'
            ),";
        }

        $values = rtrim( $values, ',' );

        $this->db->query( "INSERT INTO {$this->table} (
            review_id, 
            author_name, 
            author_photo_url, 
            rating, comment, 
            created_at
        ) VALUES {$values}" );
    }

    public function delete(): void
    {
        $this->db->query( "DELETE FROM {$this->table}" );
    }

    private function ratingNumber( string $ratingString ): int
    {
        switch ( $ratingString ) {
            case 'FIVE': 
                return 5;
            case 'FOUR':
                return 4;
            case 'THREE':
                return 3;
            case 'TWO':
                return 2;
            case 'ONE':
                return 1;
        }
    }

    public function get( int $offset, int $limit, string $order, string $sort ): array
    {
        return $this->db->get_results(
            "SELECT * 
            FROM {$this->table} 
            ORDER BY {$order} {$sort} 
            LIMIT {$limit} 
            OFFSET {$offset}",
            ARRAY_A
        );
    }

    public function total(): int
    {
        return $this->db->get_var( "SELECT COUNT(*) FROM {$this->table}" );
    }

    public function avgRating(): float
    {
        return ( float ) $this->db->get_var( "SELECT CAST(AVG(`rating`) AS DECIMAL(10,1)) FROM {$this->table}" );
    }
}