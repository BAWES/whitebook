<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Socialinfo */

$this->title = 'Create social info';
$this->params['breadcrumbs'][] = ['label' => 'Socialinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="socialinfo-create">

     <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
