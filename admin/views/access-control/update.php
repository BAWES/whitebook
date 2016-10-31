<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Accesscontrol */

$this->title = 'Update access control';
$this->params['breadcrumbs'][] = ['label' => 'Access controls', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="accesscontrol-update">

    <?= $this->render('_form', [
        'model' => $model,
        'admin' => $admin,
        'authitem' => $authitem,
        'controller' => $controller,
        'accesslist' => $accesslist,
    ]) ?>

</div>
