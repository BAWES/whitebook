<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Authrule */

$this->title = 'Create Authrule';
$this->params['breadcrumbs'][] = ['label' => 'Authrules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authrule-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
