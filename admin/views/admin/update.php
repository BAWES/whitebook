<?php

use yii\helpers\Html;

$this->title = 'Update admin';
$this->params['breadcrumbs'][] = ['label' => 'Admins', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

?>

<div class="admin-update">

    <?= $this->render('_form', [
        'model' => $model,
        'role'=>$role
    ]) ?>

</div>
