<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\VendorDraft */

$this->title = 'Create Vendor Draft';
$this->params['breadcrumbs'][] = ['label' => 'Vendor Drafts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-draft-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
