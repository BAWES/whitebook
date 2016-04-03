<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Package */

$this->title = 'Create package';
$this->params['breadcrumbs'][] = ['label' => 'Packages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="package-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
