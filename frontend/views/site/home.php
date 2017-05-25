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
                    <p class="lead"><?= Yii::t('frontend', 'You pick, we take care of the rest. Your friends will be jealous, guaranteed!') ?><br></p>
                    <div class="boxed boxed--lg bg--white text-left" style='overflow:visible'>
                        <form class="form--horizontal">
                            <div class="col-sm-12">
                                <div class="input-select" style='color:black; font-size:1.2em;'>
                                    <select onchange="location = this.value;" id="theme-selector">
                                        <option selected="selected" value="">
                                            <?= Yii::t('frontend', 'What\'s your occasion?') ?>
                                        </option>
                                        <option value="<?= Url::to('browse/all') ?>">All Themes</option>
                                        <?php foreach ($themes as $key => $value) { ?>
                                            <option value="<?= Url::to('browse/all?filter=1&themes[]='.$value['slug']) ?>">
                                                <?php if(Yii::$app->language == 'en') { 
                                                        echo ucwords($value['theme_name']);
                                                      } else { 
                                                        echo ucwords($value['theme_name_ar']);
                                                      } ?>
                                            </option>
                                        <?php } ?>
                                   </select>
                               </div>
                           </div>
                       </form>
                   </div>

                   <?php if(Yii::$app->language == "en"){ ?>
                        <a href='<?= Url::current(['language'=>'ar', ]) ?>' class='btn' style='font-size:1.2em; margin-top:10px;'>العربية</a>
                    <?php }else{ ?>
                        <a href='<?= Url::current(['language'=>'en', ]) ?>' class='btn' style='font-size:1.2em; margin-top:10px;'>English</a>
                    <?php } ?>
                   
               </div>
           </div>
       </div>
   </section>


</div>