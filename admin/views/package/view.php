<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Package */

$this->title = $model->package_id;
$this->params['breadcrumbs'][] = ['label' => 'Packages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="package-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->package_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->package_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row">
        <div class="col-lg-6">
            <div class="thumbnail">
                <img src="<?= Yii::getAlias('@s3').'/'.$model->package_background_image ?>" />
            </div>
        </div>
        <div class="col-lg-6">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'package_id',
                    'package_name',
                    'package_description:ntext',
                    'package_avg_price',
                    'package_number_of_guests',
                ],
            ]) ?>
        </div>
    </div>
</div>
