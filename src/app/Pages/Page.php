<?php

namespace GoogleReviews\Pages;

abstract class Page
{
    public function create()
    {
        add_action( 'admin_menu', [ $this, 'addPage' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueueStyles' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueueScripts' ] );
    }

    abstract public function addPage(); 

    abstract public function enqueueStyles(); 
    
    abstract public function enqueueScripts(); 
}