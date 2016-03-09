<?php
use yii\bootstrap\ActiveForm;

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use kartik\widgets\Select2;

/* @var $this \yii\web\View */
/* @var $content string */

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

AppAsset::register($this);

echo '<label class="control-label">Tag Content</label>';
echo Select2::widget([
    'name' => 'color_1',
    'value' => ['red', 'green'], // initial value
    'data' => [
        "red" => "red",
        "green" => "green",
        "blue" => "blue",
        "orange" => "orange",
        "white" => "white",
        "black" => "black",
        "purple" => "purple",
        "cyan" => "cyan",
        "teal" => "teal"
    ],
    'options' => ['placeholder' => 'Select a color ...'],
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [',', ' '],
        'maximumInputLength' => 10
    ],
]);
?>
