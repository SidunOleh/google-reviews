<?php

namespace GoogleReviews\Shortcodes;

abstract class Shortcode
{
    protected string $name;

    public function create()
    {
        add_shortcode( $this->name, [ $this, 'render' ] );
    }

    abstract public function render( $attr ): string;
}