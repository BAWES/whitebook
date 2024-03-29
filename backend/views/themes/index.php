<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\themesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Themes';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::csrfMetaTags() ?>
<div class="themes-index">
    <p>
        <?= Html::a('Create theme', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'theme_name',
             [
             'label'=>'Status',
             'format'=>'raw',
			  'value'=>function($data) {
				return HTML::a('<img src='.$data->statusImageurl($data->theme_status).' id="image" alt="Status Image" title='.$data->statusTitle($data->theme_status).'>','#',['id'=>'status',
				'onclick'=>'change("'.$data->theme_status.'","'.$data->theme_id.'")']);
				},
			],
            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => ' {update} {delete}{link}',
			],
        ],
    ]); ?>

</div>

<?php $this->registerJs("
	function change(status, id)
	{
		var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
        var path = '".Url::to(['themes/block'])."';
        $.ajax({
            type: 'POST',
            url: path,
            data: { status: status, id: id,_csrf : csrfToken},
            success: function(data) {
    			$.pjax.reload({container:'#medicine'});
            }
        });
     }
");