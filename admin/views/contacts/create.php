<?php

use yii\helpers\Html;

$this->title = 'Create contacts';
$this->params['breadcrumbs'][] = ['label' => 'Contacts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="contacts-create">

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
