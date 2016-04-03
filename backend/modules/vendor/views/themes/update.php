<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Themes */

$this->title = 'Update theme';
$this->params['breadcrumbs'][] = ['label' => 'Themes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->theme_id, 'url' => ['view', 'id' => $model->theme_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="themes-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
