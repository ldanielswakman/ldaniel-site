<?php
$current_path = str_replace($site->url(), '', $page->url());
$target_base = 'https://sincere.studio';
$target_url = $target_base . $current_path;
?>

<aside class="migrate-modal">

    <a href="javascript:clearRedirect()" class="migrate-modal__close">&times;</a>
    
    <div class="row">
        <div class="col-xs-12 col-sm-2 col-sm-offset-1 flex-centre-content">
            <figure>
                <img class="the-logo old" src="assets/images/logo.svg" alt="ldaniel.eu" />
                <img class="the-logo new" src="assets/images/logo_sincere.svg" alt="Sincere—Studio" />
            </figure>
        </div>
        <div class="col-xs-12 col-sm-5">
        <h3>ldaniel.eu ——> Sincere—Studio</h3>
        <p class="c-grey" style="line-height: 1.5rem;">Change is like a holiday! ldaniel.eu is now Sincere—Studio, along with a new version of this website.</p>

        <div class="actions u-mt2">
            <a href="<?= $target_url ?>" class="button button--outline">Take me there</a>
            <a href="javascript:clearRedirect()" class="c-greylight u-ml1" style="font-size: 0.875rem;">Cancel</a>
        </div>
    </div>
</aside>

<script>    
    // Clear timeout
    function clearRedirect() {
        clearTimeout(redirect);
        $modal.removeClass('isActive');
    }

    $(document).ready(function() {

        // Definitions
        var TIME = 6000;
        $modal = $('.migrate-modal');
        $button = $modal.find('.button--outline');

        // Set states
        $modal.addClass('isActive');
        $button.addClass('isLoading');

        // Init timeout
        redirect = setTimeout(function() {
            window.location.href = $button.attr('href');
            $button.removeClass('isLoading button--outline').addClass('button--white isDisabled');
        }, TIME);
    });
</script>

<style>
    .migrate-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        padding: 1rem;
        background: white;
        box-shadow: 0 0 2rem rgba(0, 0, 0, 0.25);
        z-index: 10;
        transform: translateY(-100%);
        opacity: 0;
    }
    @media screen and (min-width: 768px) {
        .migrate-modal { padding: 2rem; }
    }
    .migrate-modal.isActive {
        transition: all 1s ease-in-out;
        transform: translateY(0);
        opacity: 1;
    }
    .migrate-modal .migrate-modal__close {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        position: absolute;
        top: 0;
        right: 0;
        margin-right: 2rem;
        padding: 2rem;
        background: none;
        border: none;
        font-size: 2.5rem;
        color: #ccccd4;
        cursor: pointer;
    }
    .migrate-modal .migrate-modal__close:hover {
        color: #9999aa;
    }
    .migrate-modal .flex-centre-content {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .migrate-modal figure {
        position: relative;
        margin-bottom: 2rem;
    }
    .migrate-modal figure .the-logo {
        height: 5rem;
    }
    .migrate-modal figure .the-logo.old {
        transform-origin: middle left;
        animation: old-logo 5s ease-in-out infinite;
        animation-delay: 1s;
    }
    .migrate-modal figure .the-logo.new {
        position: absolute;
        top: 0;
        left: 50%;
        margin-left: -27%;
        transform-origin: middle right;
        animation: new-logo 5s ease-in-out infinite;
        animation-delay: 1s;
        opacity: 0;
    }
    @keyframes old-logo {
        0% {
            opacity: 1;
            transform: scale(1);
        }
        25% {
            opacity: 0.25
            transform: scale(0.5) translateX(-4rem);
        }
        40% {
            opacity: 0;
            transform: scale(0.25) translateX(-8rem);
        }
        90% {
            opacity: 0;
            transform: scale(1);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }
    @keyframes new-logo {
        0% {
            opacity: 0;
            transform: scale(0.25) translateX(4rem);
        }
        15% {
            opacity: 0;
            transform: scale(0.25) translateX(4rem);
        }
        40% {
            opacity: 1;
            transform: scale(1) translateX(0);
        }
        85% {
            opacity: 1;
            transform: scale(1);
        }
        100% {
            opacity: 0;
        }
    }

    .migrate-modal .actions { display: flex; align-items: center; }

    .migrate-modal .button { position: relative; overflow: hidden; }
    .migrate-modal .button:before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 0;
        background: rgba(0, 51, 85, 0.1);
        transition: width 5s linear;
        transition-delay: 1s;
    }
    .migrate-modal .button.isLoading:before {
        width: 100%;
    }
    .migrate-modal .button.isDisabled {
        pointer-events: none;
        opacity: 0.25;
    }
</style>
