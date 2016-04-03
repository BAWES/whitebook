<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Itemtype */

$this->title = 'Create item type';
$this->params['breadcrumbs'][] = ['label' => 'Itemtypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="itemtype-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
