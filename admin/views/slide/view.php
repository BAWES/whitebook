<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Slide */

$this->title = $model->slide_id;
$this->params['breadcrumbs'][] = ['label' => 'Slides', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slide-view" style="width=1000px !important; overflow:hidden">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->slide_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->slide_id], [
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
            'slide_id',
            'slide_title',
            'image:image',
            'slide_video_url:url',
            'slide_url:url',
            'slide_status',
            'sort',
            'created_datetime',
            'modified_datetime',
            'trash',
        ],
    ]) ?>

</div>
