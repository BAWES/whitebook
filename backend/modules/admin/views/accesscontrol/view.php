<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Accesscontroller;
use backend\models\Role;
/* @var $this yii\web\View */
/* @var $model backend\models\Accesscontrol */

$this->title = 'View access control';
$this->params['breadcrumbs'][] = ['label' => 'Accesscontrols', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <p>
		<?= Html::a('Back', ['index', ], ['class' => 'btn btn-success']) ?>
    </p>
<div class="accesscontrol-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
        'role_id'=> 
        [
            'label'  => 'Role Name',
            'value'  => Role::getRoleName($model->role_id),
        ],
           

        'admin_id' => 
        [
            'label'  => 'Admin Name',
            'value'  => Accesscontroller::getAdminName($model->admin_id),
        ],

        'attributes' => 
        [
            'label'  => 'Pages',
            'value'  => Accesscontroller::itemcontroller($model->controller),
        ],
        
        
            'auth_item',
        ],
    ]) ?>

</div>
