<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel common\models\VendorpackagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My package';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendorpackages-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
          
            [
              'attribute'=>'package_id',
              'label'=>'package name',
             'value'=>'package.package_name',
            ],
            'package_price',

            ['attribute'=>'package_start_date',
              'format' => ['date', 'php:d/m/Y']
            ],

            ['attribute'=>'package_end_date',
              'format' => ['date', 'php:d/m/Y']
            ],
        ],
    ]); ?>

</div>
