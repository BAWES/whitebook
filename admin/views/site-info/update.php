<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Siteinfo */

$this->title = 'Update site info: ' . ' ' . $model->app_name;
$this->params['breadcrumbs'][] = ['label' => 'Siteinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="siteinfo-update">

      <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
