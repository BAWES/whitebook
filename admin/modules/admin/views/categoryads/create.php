<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Categoryads */

$this->title = 'Create Category ads';
$this->params['breadcrumbs'][] = ['label' => 'Categoryads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categoryads-create">   

    <?= $this->render('_form', [
        'model' => $model,'category'=>$category
    ]) ?>

</div>
