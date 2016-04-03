<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Emailtemplate */

$this->title = $model->email_title;
$this->params['breadcrumbs'][] = ['label' => 'Emailtemplates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="emailtemplate-view">

      <p>
		<?= Html::a('Manage', ['index', ], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->email_template_id], ['class' => 'btn btn-primary']) ?>       
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'email_template_id:email',
            'email_title:email',
            'email_subject:email',
        ],
    ]) ?>

</div>
