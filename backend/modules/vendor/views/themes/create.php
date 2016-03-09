<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Themes */

$this->title = 'Create theme';
$this->params['breadcrumbs'][] = ['label' => 'Themes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="themes-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
