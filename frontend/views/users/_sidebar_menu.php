<?php

$cntrl = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
?>
<div class="col-md-2 hidde_res">
    <ul class="nav nav-pills nav-stacked side-bar-menu">
        <li role="presentation" class="<?=($cntrl == 'orders' && (($action == 'index') || ($action == 'view'))) ? 'active' : '';?>">
            <?=\yii\bootstrap\Html::a('<i class="fa fa-list-alt" aria-hidden="true"></i> My Order',['/orders/index']) ?>
        </li>
        <li role="presentation" class="<?=($cntrl == 'users' && (($action == 'account_settings'))) ? 'active' : '';?>">
            <?=\yii\bootstrap\Html::a('<i class="fa fa-user-circle" aria-hidden="true"></i> Account Settings',['/account-settings']) ?>
        </li>
        <li role="presentation" class="<?=($cntrl == 'users' && (($action == 'address') || ($action == 'view-address') || ($action == 'edit-address'))) ? 'active' : '';?>">
            <?=\yii\bootstrap\Html::a('<i class="fa fa-address-book-o" aria-hidden="true"></i> Address Book',['/users/address']) ?>
        </li>
        <li role="presentation" class="<?=($cntrl == 'events' && (($action == 'index') || ($action == 'detail'))) ? 'active' : '';?>">
            <?=\yii\bootstrap\Html::a('<i class="fa fa-calendar" aria-hidden="true"></i> My Events',['/events/index']) ?>
        </li>
        <li role="presentation" class="<?=($cntrl == 'things-i-like' && (($action == 'index'))) ? 'active' : '';?>">
            <?=\yii\bootstrap\Html::a('<i class="fa fa-heart-o" aria-hidden="true"></i> Things I like',['/things-i-like/index']) ?>
        </li>
    </ul>
</div>
<?=
    $this->registerCss('
    .hidde_res {background: #f2f2f2;padding: 0px;margin-right: 15px;}
    .side-bar-menu li.active a{background-color: #000;border-radius: 0px !important;}
    .side-bar-menu a{color: #000;}
    .account_setings_sections .text-center a {background-color:#000;border-color:#000;}
    .account_setings_sections { margin-top: 20px; }
    .mobile-logo-text { display: none; }
    @media only screen and (max-width: 991px) {
        #banner_sections, #inner_pages_sections {
            margin: 50px 0 0;
        }
        .border-top-yellow {
            min-height : auto;
        }  
    }      
    ')
?>