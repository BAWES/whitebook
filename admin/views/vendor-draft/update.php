<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VendorDraft */

$this->title = 'Update Vendor Draft: ' . $model->vendor_draft_id;
$this->params['breadcrumbs'][] = ['label' => 'Vendor Drafts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->vendor_draft_id, 'url' => ['view', 'id' => $model->vendor_draft_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vendor-draft-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
