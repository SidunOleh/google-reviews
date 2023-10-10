<?php foreach ( $reviews as $review ): ?>
<div class="reviews__item review">

    <div class="review__top">
        <img class="review__photo" src="<?php echo $review[ 'author_photo_url' ] ?>" alt="">
        <div class="review__inf">
            <div class="review__author">
                <?php echo $review[ 'author_name' ] ?>
            </div>
            <div class="review__rating rating">
                ★★★★★
                <div class="rating__active" style="width: <?php echo $review[ 'rating' ] / 5 * 100 ?>%;">
                    ★★★★★
                </div>
            </div>
            <div class="review__date">
                <?php echo time_elapsed_string( $review[ 'created_at' ] ) ?>
            </div>
        </div>
    </div>
    
    <div class="review__text">
        <?php if ( mb_strlen( $review[ 'comment' ] ) > 100 ): ?>
            <div class="review__excerpt">
                <?php echo mb_substr( $review[ 'comment' ], 0, 100 ) . '...' ?>
            </div>
            <div class="review__full">
                <?php echo $review[ 'comment' ] ?>
            </div>
            <div class="review__textbtn">
                <span class="readmore textbtn">
                    <?php _e( 'Read more', 'google-reviews' ) ?>
                </span>
                <span class="hidemore textbtn">
                    <?php _e( 'Hide', 'google-reviews' ) ?>
                </span>
            </div>
        <?php else: ?>
            <div>
                <?php echo $review[ 'comment' ] ?>
            </div>
        <?php endif ?>
    </div>

    <div class="review__bottom">
        <img src="<?php echo plugin_dir_url( GOOGLE_REVIEWS_ROOT . '/google-reviews.php' )  ?>/src/assets/public/img/google-icon.svg" alt="">
        <div class="review__posted">
            <span>
                <?php _e( 'Posted on', 'google-reviews' ) ?>
            </span>
            <br>
            <a href="" target="_blank">
                <?php _e( 'Google', 'google-reviews' ) ?>
            </a>
        </div>
    </div>

</div>
<?php endforeach ?>