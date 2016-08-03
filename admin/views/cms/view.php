<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Cms;

/* @var $this yii\web\View */
/* @var $model common\models\Cms */

$this->title = $model->page_name;
$this->params['breadcrumbs'][] = ['label' => 'Static page', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-view">    

    <p>
		<?= Html::a('Back', ['index', ], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'page_name',
            'page_name_ar',
            'page_content' => 
            [
                'label'  => 'page content',
                'value'  => cms::content($model->page_content),
            ],
            'page_content_ar' => 
            [
                'label'  => 'page content - Arabic',
                'value'  => cms::content($model->page_content_ar),
            ],
         
        ],
    ]) ?>

</div>
