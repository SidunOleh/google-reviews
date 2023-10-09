<?php

namespace GoogleReviews;

class Deactivator
{
    public function deactivate()
    {
        $this->deleteCronTasks();
    }

    private function deleteCronTasks()
    {
        wp_unschedule_hook( 'google_reviews_refresh' );
    }
}