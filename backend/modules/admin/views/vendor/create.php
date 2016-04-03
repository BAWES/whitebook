<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Vendor */

$this->title = 'Create vendor';
$this->params['breadcrumbs'][] = ['label' => 'Manage vendor', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-create">
    <?= $this->render('_form', [
        'model' => $model,'package' =>$package,
    ]) ?>

</div>
