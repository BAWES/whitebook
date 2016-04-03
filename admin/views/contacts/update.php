<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Contacts */

$this->title = 'Update contacts: ' . ' ' . $model->contact_name;
$this->params['breadcrumbs'][] = ['label' => 'Contacts', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="contacts-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
