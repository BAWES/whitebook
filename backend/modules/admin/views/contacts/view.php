<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Contacts */

$this->title = $model->contact_name;
$this->params['breadcrumbs'][] = ['label' => 'Contacts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contacts-view">

    <p>
		<?= Html::a('Back', ['index', ], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'contact_name',
            'contact_email:email',
            'contact_phone',
            'subject',
            'message:ntext',
        ],
    ]) ?>

</div>
