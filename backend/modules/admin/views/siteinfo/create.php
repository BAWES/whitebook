<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Siteinfo */

$this->title = 'Create Siteinfo';
$this->params['breadcrumbs'][] = ['label' => 'Siteinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="siteinfo-create">   

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
