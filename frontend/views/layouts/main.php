<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\assets\ArabicAsset;
use frontend\widgets\Alert;
use yii\helpers\Url;
use common\models\Socialinfo;
use common\models\Siteinfo;

/* @var $this \yii\web\View */
/* @var $content string */

//Google Analytics JS
$analytics = "
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-76062229-1', 'auto');
  ga('send', 'pageview');
";
$this->registerJs($analytics);


//Arabic Styling Fix
if(Yii::$app->language == 'ar'){
    ArabicAsset::register($this);
    $this->registerCssFile('@web/css/style_arabic.css');
}else{
    AppAsset::register($this);
}


$this->beginPage()
?>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <meta type="viewport" content="width=device-width , initial-scale1.0">
        <meta content="telephone=no" name="format-detection">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <link rel="apple-touch-icon" sizes="180x180" href="<?= Url::to('@web/apple-touch-icon.png') ?>">
        <link rel="icon" type="image/png" href="<?= Url::to('@web/favicon-32x32.png') ?>" sizes="32x32">
        <link rel="icon" type="image/png" href="<?= Url::to('@web/favicon-16x16.png') ?>" sizes="16x16">
        <link rel="manifest" href="<?= Url::to('@web/manifest.json') ?>">
        <link rel="mask-icon" href="<?= Url::to('@web/safari-pinned-tab.svg') ?>" color="#5bbad5">
        <meta name="theme-color" content="#ffffff">
    </head>
    <body class="has-js">
        <!-- <div class="fullpage" style="width:100%;height:100%"></div> -->
        <div id="loader2" style="display:none;text-align: center; position: fixed; width: 100%;height: 100%;z-index: 1;opacity: 0.6;background: #fff;">
        <img style="position:absolute;top:50%;" src="<?php echo Url::to('@web/images/ajax-loader.gif', true); ?>"
         title="Loader"></div>
        <!-- Header Section Start -->
        <?php
        $this->beginBody();
        $this->beginContent('@app/views/layouts/header.php');
        ?>
<?php $this->endContent(); ?>
        <!-- Header Section End -->
        <!--Content Start-->
<?= $content ?>
        <!--Content End-->
        <!-- Footer Section Start -->
<?php $this->beginContent('@app/views/layouts/footer.php', ['socialinfo' => Socialinfo::socialinformation(), 'siteinfo' => Siteinfo::siteinformation()]); ?>
<?php $this->endContent(); ?>
<?php $this->endBody() ?>
        <!-- Footer Section End -->
    </body>
</html>
<?php $this->endPage() ?>
