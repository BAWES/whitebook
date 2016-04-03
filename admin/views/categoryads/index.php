<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CategoryadsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categoryads';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categoryads-index">
    
  <p>
        <?= Html::a('Create category ads', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           [
              'header'=>'Category name',            
              'format' => 'raw',
              'value'=>function($data) {
                return $data->get_category_name($data->category_id);
                },
             ],       
             [
              'header'=>'status',           
              'format' => 'raw',
              'value'=>function($data) {
                return HTML::a('<img src='.Yii::$app->newcomponent->statusImageurl($data->status).' id="image-'.$data->advert_id.'" title='.Yii::$app->newcomponent->statusTitle($data->status).'>','javascript:void(0)',['id'=>'status', 
                'onclick'=>'change("'.$data->status.'","'.$data->advert_id.'")']);
                },
            
             ],           

            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => ' {update} {delete}{link}',
            ],
        ],
    ]); ?>

</div>
<script type="text/javascript">
    function change(status, aid)
    {       
        var csrfToken = $('meta[name="csrf-token"]').attr("content");       
        var path = "<?php echo Url::to(['/admin/advertcategory/block']); ?> ";
        $.ajax({  
        type: 'POST',      
        url: path, //url to be called
        data: { status: status, aid: aid, _csrf : csrfToken}, //data to be send
        success: function(data) {
            var status1 = (status == 'Active') ? 'Deactive' : 'Active'; 
            $('#image-'+aid).attr('src',data);
            $('#image-'+aid).parent('a').attr('onclick', 
            "change('"+status1+"', '"+aid+"')");
         }
        });
     }
</script>
