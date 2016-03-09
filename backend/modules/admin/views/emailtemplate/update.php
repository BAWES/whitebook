<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Emailtemplate */

$this->title = 'Update Emailtemplate: ' . ' ' . $model->email_title;
$this->params['breadcrumbs'][] = ['label' => 'Emailtemplates', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="emailtemplate-update">

     <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
