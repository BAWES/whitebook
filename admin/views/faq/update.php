<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Faq */

$this->title = 'Update FAQ:';
$this->params['breadcrumbs'][] = ['label' => 'Faqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="faq-update">

    <?= $this->render('_form', [
        'model' => $model,
        'faq_group_drdwn' => $faq_group_drdwn
    ]) ?>

</div>
