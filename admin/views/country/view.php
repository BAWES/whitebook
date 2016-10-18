<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->country_name;
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="country-view">    

    <p>
		<?= Html::a('Manage', ['index', ], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->country_id], ['class' => 'btn btn-primary']) ?>        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'country_name',
            'iso_country_code',
            'currency_code',
            'currency_symbol',
            ['label'=> 'country_status',
            'value'=> ($model->country_status=='A') ? 'Activated' : 'Deactivated',
            ],
            ['label'=> 'default',
            'value'=> ($model->default==1) ? 'yes' : 'No',
            ],            
        ],
    ]) ?>

</div>
