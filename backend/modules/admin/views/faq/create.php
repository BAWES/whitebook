<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Faq */

$this->title = 'Create FAQ';
$this->params['breadcrumbs'][] = ['label' => 'Faqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
