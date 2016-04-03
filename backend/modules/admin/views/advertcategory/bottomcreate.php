<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Advertcategory */

$this->title = 'Create bottom category ads';
$this->params['breadcrumbs'][] = ['label' => 'Bottom categories ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advertcategory-create">
    <?= $this->render('bottomform', [
        'model' => $model,'category'=>$category
    ]) ?>
</div>
