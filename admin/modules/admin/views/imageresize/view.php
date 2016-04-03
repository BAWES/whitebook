<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Imageresize */

$this->title = 'Image';
$this->params['breadcrumbs'][] = ['label' => 'Image resizes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imageresize-view">    

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>       
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'logo_width',
            'logo_height', 
            'item_list_width',
            'item_list_height',
            'item_detail_width',  
            'item_detail_height',    
            'item_cart_width',  
            'item_cart_height',          
        ],
    ]) ?>

</div>
