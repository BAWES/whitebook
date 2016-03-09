<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Smtp */

$this->title = $model->smtp_host;
$this->params['breadcrumbs'][] = ['label' => 'Smtps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="smtp-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>       
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'smtp_host',
            'smtp_username',
            'smtp_password',
            'smtp_port',
            'transport_layer_security',            
        ],
    ]) ?>

</div>
