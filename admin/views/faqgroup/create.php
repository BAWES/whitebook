<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model admin\models\FaqGroup */

$this->title = 'Create Faq Group';
$this->params['breadcrumbs'][] = ['label' => 'Faq Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-group-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
