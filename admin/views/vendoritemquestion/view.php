<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Vendoritemquestion */

$this->title = $model->question_id;
$this->params['breadcrumbs'][] = ['label' => 'Vendoritemquestions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendoritemquestion-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->question_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->question_id], [
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
            'question_id',
            'item_id',
            'answer_id',
            'question_text',
            'question_answer_type',
            'question_max_characters',
            'question_sort_order',
            'question_archived',
            'created_by',
            'modified_by',
            'created_datetime',
            'modified_datetime',
            'trash',
        ],
    ]) ?>

</div>
