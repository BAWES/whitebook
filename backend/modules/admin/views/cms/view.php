<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Cms;

/* @var $this yii\web\View */
/* @var $model backend\models\Cms */

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
            'page_content' => 
        [
            'label'  => 'page content',
            'value'  => cms::content($model->page_content),
        ],
         
        ],
    ]) ?>

</div>
