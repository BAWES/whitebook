<?php


use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Alert;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use frontend\assets\ArabicAsset;

//Arabic Styling Fix
if(Yii::$app->language == 'ar'){
    ArabicAsset::register($this);
}else{
    AppAsset::register($this);
}

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" dir="<?=(Yii::$app->language == 'ar') ? 'rtl' : 'ltr'; ?>">
<head>
    <?php $this->head(); ?>
</head>
<body style="background: #fff;">
    <?php $this->beginBody() ?>

    <?= $content ?>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
