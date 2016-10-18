<?php

use yii\helpers\Html;


$this->title = 'Update country: ' . ' ' . $model->country_name;
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';

?>
<div class="country-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
