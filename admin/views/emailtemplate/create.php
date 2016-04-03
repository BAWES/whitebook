<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Emailtemplate */

$this->title = 'Create template';
$this->params['breadcrumbs'][] = ['label' => 'Email templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="emailtemplate-create">

      <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
