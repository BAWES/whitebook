<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model admin\models\FaqGroup */

$this->title = 'Update Faq Group: ' . $model->faq_group_id;
$this->params['breadcrumbs'][] = ['label' => 'Faq Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->faq_group_id, 'url' => ['view', 'id' => $model->faq_group_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="faq-group-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
