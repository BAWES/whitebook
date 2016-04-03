<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Advertcategory */

$this->title = $model->advert_id;
$this->params['breadcrumbs'][] = ['label' => 'Advertcategories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advertcategory-view">

   <p>
		<?= Html::a('View', ['index', ], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->advert_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->advert_id], [
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
            'advert_id',
            'category_id',
            'advert_position',
            'advert_code:ntext',
        ],
    ]) ?>

</div>
