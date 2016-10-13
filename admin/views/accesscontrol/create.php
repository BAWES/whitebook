<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Accesscontrol */

$this->title = 'Create access control';
$this->params['breadcrumbs'][] = ['label' => 'Accesscontrols', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accesscontrol-create">

    <?= $this->render('_form', [
        'model' => $model,
        'admin' => $admin,
        'authitem' => $authitem,
        'controller' => $controller
    ]) ?>

</div>
