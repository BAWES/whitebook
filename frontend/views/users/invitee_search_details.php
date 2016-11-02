<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model  */
/* @var $form yii\widgets\ActiveForm */
?>
<?php \yii\widgets\Pjax::begin(['id' => 'itemtype']); ?>
<?=

GridView::widget([
    'dataProvider' => $dataProvider,
    // 'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'name',
        'email:email',
        'phone_number',
        ['class' => 'yii\grid\ActionColumn',
            'header' => 'Action',
            'template' => '{delete}{update}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    $url = '';
                    return Html::a('<a href="javascript:void(0)" onclick="deleteinvitee(' . $model->invitees_id . ')"><span class="glyphicon glyphicon-trash"></span></a>', $url, [
                                'title' => Yii::t('app', 'Gallery'),
                                    //'class'=>'btn btn-primary btn-xs',
                    ]);
                },
                        'update' => function ($url, $model) {
                    $url = '';
                    return Html::a('<a href="javascript:void(0)"  onclick="updateinvitee(' . $model->invitees_id . ')"><span class="glyphicon glyphicon-pencil"></span></a>', $url, [
                                'title' => Yii::t('app', 'Gallery'),
                                    //'class'=>'btn btn-primary btn-xs',
                    ]);
                },
                    ],],
            ],
        ]);
?>
        <?php \yii\widgets\Pjax::end(); ?>

<?php

$this->registerCss("
    .glyphicon-pencil{margin-left:10px;}
");
?>