<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Vendoritem */

$this->title = 'Create vendor item';
$this->params['breadcrumbs'][] = ['label' => 'Vendor items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendoritem-create">

    <?= $this->render('_form', [
        'model' => $model,'itemtype'=>$itemtype,'vendorname'=>$vendorname,'model_question' => $model_question,
       'themelist'=>$themelist,'grouplist'=>$grouplist,
    ]) ?>
</div>
