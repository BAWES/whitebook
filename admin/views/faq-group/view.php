<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model admin\models\FaqGroup */

$this->title = $model->faq_group_id;
$this->params['breadcrumbs'][] = ['label' => 'Faq Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-group-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->faq_group_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->faq_group_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'faq_group_id',
            'group_name',
            'group_name_ar',
        ],
    ]) ?>

</div>
