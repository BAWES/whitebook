<?php

use yii\web\JsExpression;
use yii\jui\AutoComplete;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AccesscontrolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Access controls';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accesscontrol-index">
<p>
	<div class="row-fluid">
       <div class="span12">
         <div class="grid simple ">
		<div class="tools">
        <?= Html::a('Create access control', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
				'attribute'=>'role_id',
				'label'=>'Role',			
				'value'=>'role.role_name',
				'filter' => Html::activeDropDownList($searchModel, 'role_id', ArrayHelper::map(admin\models\Role::find()->where(['!=','trash','Deleted'])->asArray()->all(), 'role_id','role_name'),['class'=>'form-control','prompt' => 'All']),		
			], 
			 [
				'attribute'=>'admin_id',
				'label'=>'User',		
				'value'=>function($data){
				return $data->getAdminName($data->admin_id);
				},
				'filter' => Html::activeDropDownList($searchModel, 'admin_id', ArrayHelper::map(admin\models\Admin::find()->where(['!=','trash','Deleted'])
				->andwhere(['=','admin_status','Active'])->asArray()->all(), 'id','admin_name'),['class'=>'form-control','prompt' => 'All']),		
			],
			[
				'attribute'=>'created_datetime',
				'format' => ['date', 'php:d/m/Y'],
				'label'=>'created date',			
			],
            
            ['class' => 'yii\grid\ActionColumn',
            'header'=>'Action',
            'template' => '{update} {delete}{link}',],
        ],
    ]); ?>
	   </div>
	 </div>
   </div>

</div>
