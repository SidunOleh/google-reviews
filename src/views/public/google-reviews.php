<div class="reviews">
    <div class="reviews__body">

        <div class="reviews__top">
            <div class="reviews__title">
                <?php _e( 'Google reviews that we have', 'google-reviews' ) ?>
            </div>
            <div class="reviews__close">
                <span class="close">×</span>
            </div>
        </div>

        <div class="reviews__summary">
            <div class="reviews__google">
                <img src="<?php echo plugin_dir_url( GOOGLE_REVIEWS_ROOT . '/google-reviews.php' )  ?>/src/assets/public/img/google-full-icon.svg" alt="">
                <span>
                    <?php _e( 'Rating', 'google-reviews' ) ?>
                </span>
            </div>
            <div class="reviews__total">
                <div class="reviews__avg">
                    <?php echo $avgRating ?>
                </div>
                <div class="reviews__avgrating rating">
                    ★★★★★
                    <div class="rating__active" style="width: <?php echo $avgRating / 5 * 100 ?>%;">
                        ★★★★★
                    </div>
                </div>
                <div class="reviews__totalreviews">
                    <?php printf( __( '%d reviews', 'google-reviews' ), $total ) ?>
                </div>
            </div>
            <?php if ( isset( $attr[ 'write-url' ] ) ): ?>
            <div class="reviews__write">
                <a href="<?php echo $attr[ 'write-url' ] ?>" target="_blank">
                    <?php _e( 'Write a review', 'google-reviews' ) ?>
                </a>
            </div>
            <?php endif ?>
        </div>

        <div class="reviews__items">

            <?php require GOOGLE_REVIEWS_ROOT . '/src/views/public/reviews.php' ?>

        </div>

        <?php if ( $total > 10): ?>
        <div class="reviews__load">
            <span id="load-more" data-page="1">
                <?php _e( 'Load more', 'google-reviews' ) ?>
            </span>
        </div>
        <?php endif ?>

    </div>
</div>

<div class="reviews-btn">
    <img class="reviews-btn__logo" src="<?php echo plugin_dir_url( GOOGLE_REVIEWS_ROOT . '/google-reviews.php' )  ?>/src/assets/public/img/google-icon.svg" alt="">
    <div class="reviews-btn__rating">
        <div class="reviews-btn__avg">
            <?php echo $avgRating ?>
        </div>
        <div class="reviews-btn__avgrating rating">
            ★★★★★
            <div class="rating__active" style="width: <?php echo $avgRating / 5 * 100 ?>%;">
                ★★★★★
            </div>
        </div>
    </div>
    <div class="reviews-btn__read">
        <?php printf( __( ' Read our %d reviews', 'google-reviews' ), $total ) ?>
    </div>
</div>

<script>
    // show reviews
    const reviewsBtn = document.querySelector('.reviews-btn')
    const reviews = document.querySelector('.reviews')
    const body = document.querySelector('body')
    document.querySelector('.reviews-btn__read').addEventListener('click', function(e) {
        reviewsBtn.classList.add('hide')
        reviews.classList.add('open')
        body.classList.add('reviews-open')
    })
    document.addEventListener('click', function(e) {
        if (
            (reviews.contains(e.target) && !e.target.classList.contains('close')) ||
            reviewsBtn.contains(e.target)
        ) {
            return
        }
        reviewsBtn.classList.remove('hide')
        reviews.classList.remove('open')
        body.classList.remove('reviews-open')
    })

    // show full review text
    document.addEventListener('click', function(e) {
        if (!e.target.classList.contains('textbtn')) {
            return
        }
        const reviewText = e.target.parentElement.parentElement
        reviewText.classList.toggle('full')
    })

    // load more
    const reviewsContainer = document.querySelector('.reviews__items')
    document.getElementById('load-more').addEventListener('click', function(e) {
        const page = parseInt(this.getAttribute('data-page'))
        const offset = page * 10
        const reviewsBody = document.querySelector('.reviews__body')
        reviewsBody.classList.add('loading')
        fetch(`/wp-admin/admin-ajax.php?action=load_more_reviews&offset=${offset}&limit=10`)
            .then(async(res) => {
                const data = await res.json()
                reviewsContainer.insertAdjacentHTML('beforeend', data.reviewsHtml)
                if (data.isEnd) {
                    this.classList.add('hide')
                } else {
                    this.setAttribute('data-page', page + 1)
                }
                setTimeout(() => reviewsBody.classList.remove('loading'), 500)
            }).catch(err => {
                reviewsBody.classList.remove('loading')
                console.log(err)
            })
    })
</script>

<style>
    .reviews {
        -webkit-transform: translate(-110%, 0);
        transform: translate(-110%, 0);
        -webkit-transition: 0.3s all linear;
        transition: 0.3s all linear;
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        height: 100vh;
        max-width: 450px;
        font-family: sans-serif;
        overflow: auto;
        background-color: white;
    }
    
    .reviews.open {
        -webkit-transform: translate(0, 0);
        transform: translate(0, 0);
    }
    
    .reviews__body {
        padding: 25px;
    }
    
    .reviews__top {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        gap: 40px;
        margin-bottom: 15px;
    }
    
    .reviews__title {
        font-size: 24px;
        font-weight: 500;
        color: #1C1919;
        text-transform: uppercase;
    }
    
    .reviews__close {
        -ms-flex-negative: 0;
        flex-shrink: 0;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background-color: #C29E6B;
        color: white;
        cursor: pointer;
        text-align: center;
        font-size: 22px;
    }
    
    .reviews__close:hover {
        background-color: #c28734;
    }
    
    .reviews__summary {
        background-color: #EAEAEA;
        padding: 20px;
        margin-bottom: 15px;
    }
    
    .reviews__google {
        margin-bottom: 15px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 10px;
    }
    
    .reviews__google span {
        font-size: 20px;
        font-weight: 500;
        color: #444444;
    }
    
    .reviews__total {
        margin-bottom: 15px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 5px;
    }
    
    .reviews__avg {
        color: #C29E6B;
    }
    
    .reviews__avgrating {
        font-size: 18px;
    }
    
    .reviews__totalreviews {
        color: #8A8683;
    }
    
    .reviews__write a {
        display: inline-block;
        padding: 20px;
        background-color: #444444;
        color: white;
        text-decoration: none;
        cursor: pointer;
    }
    
    .reviews__write a:hover {
        background-color: #302f2f;
    }
    
    .reviews__items {
        margin-bottom: 25px;
    }
    
    .reviews__load {
        text-align: center;
    }
    
    .reviews__load span {
        display: inline-block;
        padding: 20px 40px;
        background-color: #C29E6B;
        color: white;
        cursor: pointer;
    }
    
    .reviews__load span.hide {
        display: none;
    }
    
    .reviews__load span:hover {
        background-color: #c28734;
    }
    
    .review {
        background-color: #EAEAEA;
        padding: 25px;
        margin-bottom: 25px;
    }
    
    .review__top {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .review__photo {
        border-radius: 50%;
        width: 65px;
    }
    
    .review__author {
        font-size: 16px;
        font-weight: 500;
        color: #1C1919;
        text-transform: uppercase;
    }
    
    .review__rating {
        font-size: 25px;
    }
    
    .review__date {
        font-size: 14px;
        color: #8A8683;
    }
    
    .review__text {
        margin-bottom: 15px;
        font-size: 16px;
        color: #1C1919;
        line-height: 18px;
    }
    
    .review__text.full .review__full {
        display: block;
    }
    
    .review__text.full .review__excerpt {
        display: none;
    }
    
    .review__text.full .review__textbtn .hidemore {
        display: inline;
    }
    
    .review__text.full .review__textbtn .readmore {
        display: none;
    }
    
    .review__full {
        display: none;
    }
    
    .review__textbtn {
        margin-top: 10px;
        color: #C29E6B;
        cursor: pointer;
    }
    
    .review__textbtn .hidemore {
        display: none;
    }
    
    .review__bottom {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 10px;
    }
    
    .review__posted span {
        color: #959595;
    }
    
    .review__posted a {
        color: #448DE3;
    }
    
    .rating {
        color: grey;
        position: relative;
        letter-spacing: 2px;
    }
    
    .rating__active {
        color: gold;
        position: absolute;
        top: 0;
        left: 0;
        width: 0%;
        overflow: hidden;
    }
    
    .reviews-btn {
        position: fixed;
        z-index: 1000;
        top: 50%;
        left: 20px;
        -webkit-transform: translate(0, -50%);
        transform: translate(0, -50%);
        padding: 20px 10px;
        background-color: white;
        -webkit-box-shadow: 0px 4px 17px 0px #1E2D441F;
        box-shadow: 0px 4px 17px 0px #1E2D441F;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 5px;
    }
    
    .reviews-btn.hide {
        opacity: 0 !important;
        visibility: hidden;
    }
    
    .reviews-btn__logo {
        margin-bottom: 4px;
    }
    
    .reviews-btn__rating {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 5px;
    }
    
    .reviews-btn__avg {
        color: #C29E6B;
    }
    
    .reviews-btn__read {
        color: #444444;
        text-decoration: underline;
        cursor: pointer;
    }
    
    .reviews-btn__read:hover {
        color: #302f2f;
    }
    
    body.reviews-open::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: black;
        opacity: 0.3;
    }
    
    .reviews::-webkit-scrollbar {
        width: 0.5em;
        height: 0.5em;
    }
    
    .reviews::-webkit-scrollbar-thumb {
        background-color: #DBDBDB;
        border-radius: 3px;
    }
    
    .reviews::-webkit-scrollbar-thumb:hover {
        background: #908e8e;
    }
    
    .loading {
        position: relative;
    }
    
    .loading::before {
        content: "";
        position: absolute;
        z-index: 100000;
        top: 0;
        left: 0;
        background: -webkit-gradient(linear, left top, right bottom, color-stop(40%, #eeeeee), color-stop(50%, #dddddd), color-stop(60%, #eeeeee));
        background: linear-gradient(to bottom right, #eeeeee 40%, #dddddd 50%, #eeeeee 60%);
        background-size: 200% 200%;
        background-repeat: no-repeat;
        -webkit-animation: placeholderShimmer 2s infinite linear;
        animation: placeholderShimmer 2s infinite linear;
        height: 100%;
        width: 100%;
        opacity: 0.6;
    }
    
    @-webkit-keyframes placeholderShimmer {
        0% {
            background-position: 100% 100%;
        }
        100% {
            background-position: 0 0;
        }
    }
    
    @keyframes placeholderShimmer {
        0% {
            background-position: 100% 100%;
        }
        100% {
            background-position: 0 0;
        }
    }
</style>