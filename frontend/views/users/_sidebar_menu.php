<?php

$cntrl = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
?>
<div class="col-md-2 hidde_res">
    <ul class="nav nav-pills nav-stacked side-bar-menu">
        <li role="presentation" class="<?=($cntrl == 'orders' && (($action == 'index') || ($action == 'view'))) ? 'active' : '';?>">
            <?=\yii\bootstrap\Html::a('My Order',['/orders/index']) ?>
        </li>
        <li role="presentation" class="<?=($cntrl == 'users' && (($action == 'account_settings'))) ? 'active' : '';?>">
            <?=\yii\bootstrap\Html::a('Account Settings',['/account-settings']) ?>
        </li>
        <li role="presentation" class="<?=($cntrl == 'users' && (($action == 'address') || ($action == 'view-address') || ($action == 'edit-address'))) ? 'active' : '';?>">
            <?=\yii\bootstrap\Html::a('Address Book',['/users/address']) ?>
        </li>
        <li role="presentation">
            <?=\yii\bootstrap\Html::a('My Events',['/events/index?slug=events']) ?>
        </li>
    </ul>
</div>

<?=
    $this->registerCss('
    .hidde_res {background: #f2f2f2;padding: 0px;margin-right: 15px;}
    .side-bar-menu li.active a{background-color: #000;border-radius: 0px !important;}
    .side-bar-menu a{color: #000;}
    .text-center a {background-color:#000;border-color:#000;}
    ')
?>