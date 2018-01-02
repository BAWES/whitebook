<?php

use yii\helpers\Url;

?>

<div class="notification pos-top pos-right search-box bg--white border--bottom" data-animation="from-top" data-notification-link="search-box">
    <form>
        <div class="row">
            <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                <input type="search" name="query" placeholder="Search for..." />
            </div>
        </div>
        <!--end of row-->
    </form>
</div>
<!--end of notification-->

<div class="main-container">

    <section class="cover cover-fullscreen imagebg text-center height-100" data-overlay="4">

      <div class="background-image-holder"><img alt="background" src="images/tourism-9.jpg"></div>
        <div class="container pos-vertical-center">
            <div class="row">
                <div class="col-sm-12">
                    <img src='images/logo-white.svg' style='width:500px; max-width:100%; padding:30px;'/>
                    <h1><?= Yii::t('frontend', 'Book everything you need for your event') ?></h1>
                    <p class="lead"><?= Yii::t('frontend', 'Coming Soon') ?><br></p>


               </div>
           </div>
      </div>
    </section>

</div>
