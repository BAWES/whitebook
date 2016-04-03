<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Socialinfo */

$this->title = 'Update social info';
$this->params['breadcrumbs'][] = ['label' => 'Socialinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="socialinfo-update">

     <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
