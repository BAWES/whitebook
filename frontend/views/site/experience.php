<?php

use yii\helpers\Url;
use yii\helpers\Html;
?>
<!-- coniner start -->

<section id="inner_pages_white_back">
    <div class="container paddng0">
        <div class="exp_sect">
            <div class="plan_inner_sec">
                <h2><?= Yii::t("frontend", "Experience") ?></h2>
                <h5 class="text-center"><?= Yii::t("frontend", "Experience is a list of value added services provided by The White Book's team") ?></h5>
            </div>
        </div>

        <div class="text-center">
            <div class='row'>
                <h3 class="margin-top-1-2"><?= Yii::t("frontend", "Boost your Event!") ?></h3>

                <p class="provide-event-class">
                    <?= Yii::t("frontend", "We provide event boosting services where we assist with brainstorming and researching ideas, suggesting vendors and ideas to implement at your event, getting quotations from different vendors and booking them for the event"); ?>
                </p>
            </div>
            <div class='row'>
                <div class='col-md-6'>

                    <h4><?= Yii::t("frontend", "BBS Ramadan Bazaar") ?></h4>
                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/QYA6S395lMw" frameborder="0" allowfullscreen></iframe>

                </div>

                <div class='col-md-6'>

                    <h4><?= Yii::t("frontend", "Founders & Investors Event") ?></h4>
                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/4b4S0oTyBK4" frameborder="0" allowfullscreen></iframe>
                </div>

            </div>

        </div>


    </div>
</section>
<!-- continer end -->

<?php
// category display dynamic
/* foreach ($category as $key => $value) { ?>
  <li>
  <a href="<?= Url::to('products/'.$value['slug']); ?>" title="<?= $value['category_name'] ?>">
  <span class="<?= $value['slug'] ?>" style="background: url(../../images/sprit2.png) -3px -17px no-repeat;display: inline-block;
  width: 100%;  height: 50px;"></span>
  <span class="responsi_common"><?= $value['category_name'] ?></span>
  </a>
  </li>
  <?php } */
?>
<!-- REMOVE THE SCRIPT ONCE PAGE WORKOUT !IMPORTANT-->
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
                    })
                }

            }

            $(window)
                    .scroll(positionFooter)
                    .resize(positionFooter)

        });
    }

</script>

<?php
$this->registerCss("
.margin-top-1-2{margin-top:1.2em;}
.provide-event-class{font-size:1.1em; margin:1.5em auto; width:85%}
");
?>
<!-- END REMOVE THE SCRIPT ONCE PAGE WORKOUT !IMPORTANT-->
