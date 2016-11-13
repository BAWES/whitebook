<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Role */

$this->title = 'Create role';
$this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-create">

     <?= $this->render('_form', [
        'model' => $model,
        'action_list' => $action_list,
        'role_access_list' => []
    ]) ?>

</div>
