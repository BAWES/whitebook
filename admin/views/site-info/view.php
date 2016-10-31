<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Siteinfo */

$this->title = $model->app_name;
$this->params['breadcrumbs'][] = ['label' => 'Siteinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siteinfo-view">   
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'home_slider_alias'
        ],
    ]) ?>

</div>
