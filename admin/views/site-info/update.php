<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Siteinfo */

$this->title = 'Update Site Info';
$this->params['breadcrumbs'][] = 'Site Info';
?>
<div class="siteinfo-update">

      <?= $this->render('_form', [
        'data' => $data,
    ]) ?>

</div>
