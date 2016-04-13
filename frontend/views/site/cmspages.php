<?php
use yii\helpers\Url;

$this->title = $title . ' | Whitebook';
?>


<link href="<?= Url::to("@web/css/bootstrap-select.min.css") ?>" rel="stylesheet">
<section id="inner_page_detials">
    <div class="top_sections_titles">
        <div class="container">
            <div class="title_main">
                <h1><?= $title ?></h1>
            </div>
            <div class="about_content_sec">
                <h5><?= $content ?></h5>
            </div>
        </div>
    </div>
</section>

<script>
// Window load event used just in case window height is dependant upon images
    if (jQuery(window).width() > 991) {
        $(window).bind("load", function () {

            var footerHeight = 0,
                    footerTop = 0,
                    $footer = $("#footer_sections");

            positionFooter();

            function positionFooter() {

                footerHeight = $footer.height();
                footerTop = ($(window).scrollTop() + $(window).height() - footerHeight) + "px";

                if (($(document.body).height() + footerHeight) < $(window).height()) {
                    $footer.css({
                        position: "absolute"
                    })
                } else {
                    $footer.css({
                        position: "static"
                    }).animate({
                        bottom: footerTop
                    })
                }

            }

            $(window)
                    .scroll(positionFooter)
                    .resize(positionFooter)

        });

    }
// mobile hover menu start
    $(".mobile-menu .dropdown").click(function () {
//  $('.dropdown-menu1', this).stop(true, true).slideDown("fast");
        $(this).toggleClass('open');
    },
            function () {
//  $('.dropdown-menu1', this).stop(true, true).slideUp("fast");
                $(this).toggleClass('open');
            }
    );

// mobile hover menu end

</script>
<!--end footer sticky-->
