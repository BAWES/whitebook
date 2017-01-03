<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;

/* @var $searchModel common\models\themesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Themes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="themes-index">
<p>
        <?= Html::a('Create themes', ['create'], ['class' => 'btn btn-success']) ?>
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
				return HTML::a('<img src='.$data->statusImageurl($data->theme_status).' id="image-'.$data->theme_id.'" alt="Status Image" title='.$data->statusTitle($data->theme_status).'>','javascript:void(0)',['id'=>'status', 
				'onclick'=>'change("'.$data->theme_status.'","'.$data->theme_id.'")']);
				},
			],
			[
				'attribute'=>'created_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',			
			],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Action',
                'template' => '{update} {move} {delete}',
                'buttons' => [
                    'move' => function ($url, $model) {     
                        return '<a title="'.Yii::t('yii', 'Move').'" data-theme-name="'.$model->theme_name.'" data-theme-id="'.$model->theme_id.'" class="btn-move"><span class="glyphicon glyphicon-share"></span></a>';  
                    }
                ]
			],
        ],
    ]); ?>

</div>

<div id="modal_theme" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Select theme</h4>
      </div>
      <div class="modal-body">

            <p>Select theme to move all items from "<span class="theme_name"></span>" theme.</p>

            <?= Html::hiddenInput('old_theme_id', 0, ['id' => 'old_theme_id']); ?>

            <select class="form-control" id="new_theme_id">
                <?php foreach ($themes as $key => $value) { ?>
                    <option value="<?= $value->theme_id ?>">
                        <?= $value->theme_name ?>
                    </option>
                <?php } ?>
            </select>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-move-submit" type="button">Submit</button>
      </div>
    </div>
  </div>
</div>

<?php 

$this->registerJs("

    var csrfToken = $('meta[name=\"csrf-token\"]').attr(\"content\"); 

    $(document).delegate('.btn-move', 'click', function() {
        $('span.theme_name').html($(this).attr('data-theme-name'));
        $('#old_theme_id').val($(this).attr('data-theme-id'));
        $('#modal_theme').modal('show');
    });

    $(document).delegate('.btn-move-submit', 'click', function() {
        $.ajax({  
            type: 'POST',      
            url: '".Url::to(['themes/move-items'])."', //url to be called
            data: { 
                old_theme_id: $('#old_theme_id').val(), 
                new_theme_id: $('#new_theme_id option:selected').val(), 
                _csrf : csrfToken
            }, 
            success: function(json) {

                var html  = '<div class=\"alert alert-success\">';
                html += '<button class=\"close\" data-dismiss=\"alert\"></button>';
                html += json.message;
                html += '</div>';

                $('.breadcrumb').before(html);

                $('#modal_theme').modal('hide');

                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }
        });
    });

    function change(status, id)
    {       
        var csrfToken = $('meta[name=\"csrf-token\"]').attr(\"content\");       
        var path = \"".Url::to(['/themes/block'])."\";
        
        $.ajax({  
            type: 'POST',      
            url: path, //url to be called
            data: { status: status, id: id, _csrf : csrfToken}, //data to be send
            success: function(data) {
                var status1 = (status == 'Active') ? 'Deactive' : 'Active'; 
                $('#image-'+id).attr('src',data);
                $('#image-'+id).parent('a').attr('onclick', 
                \"change('\"+status1+\"', '\"+id+\"')\");
            }
        });
    }

", View::POS_HEAD);
	