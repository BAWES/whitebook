<?php

use admin\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Alert;
use yii\helpers\Url;

AppAsset::register($this);
$this->beginPage();

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta charset="utf-8" />
    <?= Html::csrfMetaTags() ?>
    <title>Whitebook</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <?php $this->head(); ?>

     <link rel="apple-touch-icon" sizes="180x180" href="<?= Url::to('@web/apple-touch-icon.png') ?>">
     <link rel="icon" type="image/png" href="<?= Url::to('@web/favicon-32x32.png') ?>" sizes="32x32">
     <link rel="icon" type="image/png" href="<?= Url::to('@web/favicon-16x16.png') ?>" sizes="16x16">
     <link rel="manifest" href="<?= Url::to('@web/manifest.json') ?>">
     <link rel="mask-icon" href="<?= Url::to('@web/safari-pinned-tab.svg') ?>" color="#5bbad5">
     <meta name="theme-color" content="#ffffff">

</head>
<body class="">
    <?php $this->beginBody() ?>
    <!-- BEGIN CONTENT -->
    <div class="page-container row-fluid">
        <!-- BEGIN PAGE CONTAINER-->
        <div class="container page-content">
            <div class="content">

                <div class="message_wrapper">
                    <?php
                    if ($flash = Yii::$app->session->getFlash('success')) {
                        echo Alert::widget(['options' => ['class' => 'alert-success'], 'body' => $flash]);
                    }
                    if ($flash = Yii::$app->session->getFlash('danger')) {
                        echo Alert::widget(['options' => ['class' => 'alert-danger'], 'body' => $flash]);
                    }
                    ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="grid simple">
                            <div class="grid-body no-border"> <br>
                                <div class="row">
                                    <?= $content ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTAINER -->
        <?php $this->beginContent('@app/views/layouts/footer.php'); ?>
        <!-- You may need to put some content here -->
        <?php $this->endContent(); ?>
        <?php $this->endBody() ?>
        </body>
    </html>
<?php $this->endPage() ?>
