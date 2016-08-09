<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\view;

/* @var $this yii\web\View */
/* @var $searchModel admin\models\FaqGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Faq Groups';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-group-index">

    <p>
        <?= Html::a('Create Faq Group', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'group_name',
            'group_name_ar',
            [
                'attribute'=>'sort_order',
                'label'=>'Sort Order',  
                'format' => 'raw',      
                'value'=>function($data){
                    return '<b><input type="hidden" id="hidden_'.$data->faq_group_id.'" value="'.$data->sort_order.'"><input type="text" value="'.$data->sort_order.'" onblur="change_sort_order(this.value,'.$data->faq_group_id.')"></b>';
                    },
                     'contentOptions'=>['class'=>'sort','style'=>'max-width: 100px;']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>
</div>

<?php

$this->registerJS("
         
    function change_sort_order(sort_val, faq_group_id)
    {
         var exist_sort=$('#hidden_'+faq_group_id).val();

         if(sort_val != exist_sort || exist_sort==0)
         {
            if(sort_val<=0 && sort_val!='')
            {
                $('#hidden_'+faq_group_id).next(':input').val(exist_sort);
                alert('Please enter greater than 0!');
                return false;
            }
            
            if(isNumeric(sort_val))
            {
                var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');     
                var path = '".Url::to(['/faqgroup/sort_faq_group'])."';
                $.ajax({  
                type: 'POST',      
                url: path, //url to be called
                data: { sort_val: sort_val, faq_group_id: faq_group_id,_csrf : csrfToken}, //data to be send
                success: function(data) {
                    if(data)
                    {
                        location.reload();
                    }
                 }
                });
            }
            else
            {
                if(sort_val!='')
                {
                    alert('Enter only integer values!');
                    return false;
                }
            }
        }
    }
    
    function isNumeric(n)
    {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
", View::POS_HEAD);

