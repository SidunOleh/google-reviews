<?php

namespace GoogleReviews\Models;

abstract class Model
{    
    protected $db;

    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
    }
}