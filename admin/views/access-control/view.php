<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\AccessController;
use common\models\Role;
/* @var $this yii\web\View */
/* @var $model common\models\Accesscontrol */

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
                'value'  => AccessController::getAdminName($model->admin_id),
            ],
           'attributes' => 
            [
                'label'  => 'Pages',
                'value'  => AccessController::itemcontroller($model->controller),
            ],
            'auth_item',
        ],
    ]) ?>

</div>
