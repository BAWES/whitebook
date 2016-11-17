<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\models\VendorItem;
use common\models\Category;
use yii\grid\GridView;
use yii\web\view;
use common\components\CFormatter;

$this->title = 'My Event | '.$event_details->event_name;


?>
<!-- coniner start -->
    <section id="inner_pages_sections">
        <div class="container">
            <div class="title_main">
                <h1><?php echo Yii::t('frontend','Events'); ?></h1>
            </div>


            <div class="account_setings_sections">
                <?=$this->render('/users/_sidebar_menu');?>
                <div class="col-md-9 border-left">
                    <div class="events_detials_common">
<div class="events_inner_contents_new">
<div class="col-md-10 padding0">
<div class="events_inner_descript">
    <h3>
        <?php echo $event_details->event_name; ?>
        <a class="edit_content" href="#" title="( Edit )" onclick="editevent(<?php echo $event_details->event_id;?>)">( Edit )</a>
    </h3>
    <p><?php echo date('d-m-Y',strtotime($event_details->event_date)); ?></p>
    <label><?php echo $event_details->event_type; ?></label>
</div>
</div>
<div class="col-md-2 padding-left0">
    <div class="select_butons">
        <a href="#invitee" title="<?= Yii::t('frontend', 'Invitees') ?>" type="button" class="btn btn-warning">
            <?= Yii::t('frontend', 'Invitees') ?>
        </a>
    </div>
</div>
</div>
<!--events detail start-->
<div class="col-md-12 padding0">
<div class="product_detials_common normal_tables">

<div class="col-md-12 paddng0">
<div class="right_descr_product">

<div class="accad_menus">
<div class="panel-group row" id="accordion">

<?php

$cust_id = Yii::$app->user->getId();

foreach ($cat_exist as $key => $value1) {

$items = VendorItem::find()
    ->select(['{{%vendor}}.vendor_name','{{%vendor_item}}.item_id','{{%event_item_link}}.link_id',
        '{{%image}}.image_path','{{%vendor_item}}.item_price_per_unit',
        '{{%vendor_item}}.item_name','{{%vendor_item}}.slug', '{{%vendor_item}}.item_id'
    ])
    ->innerJoin('{{%event_item_link}}', '{{%event_item_link}}.item_id = {{%vendor_item}}.item_id')
    ->leftJoin('{{%image}}', '{{%image}}.item_id = {{%vendor_item}}.item_id')
    ->leftJoin('{{%vendor}}', '{{%vendor}}.vendor_id = {{%vendor_item}}.vendor_id')
    ->leftJoin(
        '{{%vendor_item_to_category}}', 
        '{{%vendor_item_to_category}}.item_id = {{%vendor_item}}.item_id'
    )
    ->where([
        '{{%vendor_item}}.item_status' => 'Active',
        '{{%vendor_item}}.trash' => 'Default',
        '{{%vendor_item}}.item_for_sale' => 'Yes',
        '{{%vendor_item_to_category}}.category_id' => $value1['category_id'],
        '{{%vendor_item}}.type_id' => 2,
        '{{%event_item_link}}.trash' => 'Default',
        '{{%event_item_link}}.event_id' => $event_details->event_id
    ])
    ->andWhere('{{%image}}.image_path != ""')    
    ->asArray()
    ->all();
?>
<div class="panel panel-default">
<div class="panel-heading" role="tab" id="heading<?= $key ?>">
    <h4 class="panel-title">
        <a data-toggle="collapse" id="description_click" data-parent="#accordion" href="#collapse<?= $key ?>" aria-expanded="false" aria-controls="collapse<?= $key ?>" class="collapsed">

        <?php if(Yii::$app->language == "en"){
                echo $value1['category_name'].' - '.'<span data-cateogry-id="'.$value1['category_id'].'" id="item_count">' .count($items). '</span>';
              }else{
                echo $value1['category_name_ar'].' - '.'<span id="item_count">' .count($items). '</span>';
              }
        ?>

        <span class="glyphicon glyphicon-menu-right text-align pull-right"></span></a>
    </h4>
</div>
<div id="collapse<?= $key ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?= $key ?>" aria-expanded="false">
<div class="panel-body">
<div class="events_inner_listing_new">
<div class="events_listing">
<ul>
<?php
if(!empty($items))
{
    foreach ($items as $key => $value) {
?>
<li>
    <div class="events_items">
        <div class="events_images">
            <div class="hover_events">
                <div class="event_delete_icons">
                    <a href="javascript:void(0)" id="<?= $value['link_id']; ?>" onclick="deleteeventitem('<?= $value['link_id']; ?>','<?= $value1['category_name']; ?>','<?= $value1['category_id']; ?>','<?= $event_details->event_id; ?>',this.id)" title="Delete"></a>
                </div>
                <?php 
                
                $k = array();
                
                foreach($customer_events_list as $l){
                    $k[] = $l['item_id'];
                }

                $result = array_search($value['item_id'], $k);

                if (is_numeric ($result)) { ?>
                    <div class="faver_icons faverited_icons"><a  href="javascript:;" role="button" id="<?php echo $value['item_id']; ?>"  class="add_to_favourite" name="add_to_favourite" title="<?php echo Yii::t('frontend','Add to Things I Like');?>"></a>
                    </div> 
                <?php } else { ?>
                    <div class="faver_icons">
                        <a  href="javascript:;" role="button" id="<?php echo $value['item_id']; ?>"  class="add_to_favourite" name="add_to_favourite" title="<?php echo Yii::t('frontend','Add to Things I Like');?>"></a>
                    </div>
                <?php }?>
            
            </div><!-- END .hover_events -->
            
            <?= Html::a(Html::img(Yii::getAlias("@vendor_item_images_210/").$value['image_path'],['class'=>'item-img']),Url::toRoute(['/browse/detail/','slug'=>$value['slug']])) ?>
        </div><!-- END .events_images -->

        <div class="events_descrip">

            <?= Html::a($value['vendor_name'], Html::img(Yii::getAlias("@vendor_item_images_210/").$value['image_path'],['class'=>'item-img'])) ?>
            
            <h3><?= $value['item_name']  ?></h3>

            <p><?= CFormatter::format($value['item_price_per_unit']) ?></p>
        </div><!-- END .events_descrip --> 
    </div><!-- END .events_items -->
</li>
<?php 
    } //foreach items 
} //if items 
?>
</ul>
<div class="events_brows_buttons_common">
    <div class="margin_0_auto">
        <a href="<?= Url::toRoute(['/browse/list/','slug'=>$value1['slug']]);?>" class="btn btn-danger">
            <?= Yii::t('frontend','Browse the Category');?>
        </a>
    </div>
</div>

</div>
</div>
</div>
</div>
</div>
<?php  
}//for each category 
?>
<!-- heading seven end -->

<div class="invates_common" id="invitee">
<h4><?= Yii::t('frontend','Invitees');?></h4>
<p><?= Yii::t('frontend','Invite your friends, relatives for this event'); ?></p>
<div class="invite_error color-red" style="display:none;">
    <?= Yii::t('frontend','Email already exist with this event.'); ?>    
</div>
<div class="add_detials_form">
    <div data-example-id="basic-forms" class="bs-example">
        <form>
            <div class="col-md-4 padding-right0">
                <div class="form-group">
                    <input type="hidden" placeholder="id" name="invitees_id" id="invitees_id" class="form-control">
                    <input type="text" placeholder="<?= Yii::t('frontend','Name') ?>" name="invitees_name" id="invitees_name" class="form-control">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <input type="email" placeholder="<?= Yii::t('frontend','Email');?>" name="invitees_email" id="invitees_email" class="form-control">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <input type="phone" placeholder="<?= Yii::t('frontend','Phone');?>" name="invitees_phone" id="invitees_phone" class="form-control">
                </div>
            </div>

            <div class="col-md-1 padding0">
                <div class="add_events_new">
                    <input type="button" class="btn btn-default" id="submit" value="<?= Yii::t('frontend','Add');?>" onClick="addinvitees()">
                </div>
            </div>
        </form>
    </div>
</div>
<div class="left_search_content">
<div data-example-id="basic-forms" class="bs-example">
    <form>
    <?php $this->render('_search.php',['event_details'=>$event_details]); ?>
    <div class="col-md-8 padding0">
    <div class="col-md-3 pull-left text-left padding0 desktop-hide">
    <div class="input-group">
    <div id="navigation-bar">
    <form id="search" action="#" method="post">
        <div id="input3" class="right_slider">

            <input type="text" placeholder="<?= Yii::t('frontend','Name/Phone/Email');?>" id="inviteesearch1" class="form-control">

            <span class="input-group-btn mobile-search-icon">
                <button class="btn btn-default" type="button" onClick="Searchinvitee('<?php echo $event_details->event_id;?>')"><?= Yii::t('frontend','Go!');?></button>
            </span>
        </div>
        <div id="label3">
        <div id="search1" class="search_for"></div>
        <label for="search-terms" id="search-labl3"></label></div>
    </form>
</div>

</div><!-- /input-group -->
</div>
<div class="prient_common">
<a href="<?php echo Url::toRoute(['/events/export/','id'=>$event_details->event_id]);?>" title="<?= Yii::t('frontend','Export to Excel');?>"><?= Yii::t('frontend','Export to Excel');?></a>
<a href="#" title="Print" onclick="window.print()"><?= Yii::t('frontend','Print');?></a>
</div>
</div>

</form>
</div>
</div>
<div class="add_contact_table">
    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'emptyText' => Yii::t('frontend', 'Ops, nothing to show!'),
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'header' => Yii::t('frontend', 'Name'),
                    'attribute' => 'name',
                ],
                [
                    'header' => Yii::t('frontend', 'Email'),
                    'attribute' => 'email',
                ],
                [
                    'header' => Yii::t('frontend', 'Phone'),
                    'attribute' => 'phone_number',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header'=> Yii::t('frontend', 'Action'),
                    'template' => '{delete}{update}',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            $url = Url::toRoute(['events/delete-invitee','id'=>$model->invitees_id]);
                            return  Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'title' => Yii::t('app', 'Gallery'),'onClick' => 'return (confirm("Are you sure you want to delete this invitee?"))'
                            ]);
                        },
                        'update' => function ($url, $model) {
                            $url = '';
                            return  Html::a('<a href="javascript:void(0)"  onclick="inviteeDetail('.$model->invitees_id.')"><span class="glyphicon glyphicon-pencil margin-left-10" ></span></a>', $url, [
                            'title' => Yii::t('app', 'Gallery'),
                            ]);
                        },
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="similar_product_listing">
<div class="feature_product_slider">
</div>
</div><!--events detail end-->
</div>
                </div>
            </div>
        </div>
</section>
<!-- continer end -->

<?php
$this->registerCss("
.item-img{width:210px; height:208px;}
.margin-left-10{margin-left:10px;}
.msg-success{margin-top: 5px; width: 320px; float: left; text-align: left;}
.color-red{color:red;}
table{    font-size: 12px;}
.header-updated{padding-bottom:0; margin-bottom: 0;}
.body-updated{background: white; margin-top: 0;}
#inner_pages_sections .container{background:#fff; margin-top:12px;}
.border-left{border-left: 1px solid #e2e2e2;}
");

$this->registerJs("
    var event_id = '".$event_details->event_id."';
    var event_name = '".$event_details->event_name."';
    var add_invite = '".Url::toRoute('/events/add-invitee')."';
    var invite_detail = '".Url::toRoute('/events/invitee-details')."';
    var update_invite = '".Url::toRoute('/events/add-invitee')."';
    var delete_invite = '".Url::toRoute('/events/add-invitee')."';
    /* BEGIN Insert invitees for respective event */


    /* BEGIN EDIT EVENT */
    function editevent(event_id)
    {
        jQuery.ajax({
            type:'POST',
            url: '".Yii::t('frontend', Url::toRoute('/events/event-details'))."',
            data:{
                'event_id':event_id
            },
            success:function(data)
            {

                jQuery('#editeventModal').html(data);
                jQuery('.selectpicker').selectpicker('refresh');
                jQuery('#edit_event_date').datepicker({
                    format: 'dd-mm-yyyy',
                    startDate:'today',
                    autoclose:true,
                });
                jQuery('#EditeventModal').modal('show');
            }
        });
    }
    /* END Insert invitees for respective event */

    /* Event detail slide items !IMPORTANT * Mariyappan */
    jQuery(document).ready(function () {
        jQuery('#collapse0').attr('aria-expanded', 'true');
        jQuery('#collapse0').attr('class', 'panel-collapse collapse in');
    });

    /* Event detail slide items !IMPORTANT * Mariyappan */
    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    jQuery('label#search-labl3').click(function(){
        jQuery.pjax.reload({container:'#invitee-grid'});
    });

    /* BEGIN USER CAN DELETE THE ITEM FOR THE PARTICULAR EVENT */
    function deleteeventitem(item_link_id, category_name,category_id,event_id,tis)
    {
        var r = confirm('".Yii::t('frontend', 'Are you sure you want to delete this item?')."');

        if (r == true) {

            jQuery.ajax({
                url: '".Url::to(['/users/deleteeventitem'])."',
                type:'POST',
                data: {
                    'item_link_id':item_link_id,
                    'category_id':category_id,
                    'event_id':event_id
                },
                success:function(data)
                {
                    if(data!=-1)
                    {
                        jQuery('#'+tis).parents('.panel-default').find('span#item_count').html(data);
                        jQuery('#'+tis).parents('li').remove();
                        jQuery('#login_success').modal('show');
                        jQuery('#login_success #success').html('<span class=\"sucess_close\">&nbsp;</span><span class=\"msg-success\" >Success! Item removed from the '+category_name+'.</span>');
                        window.setTimeout(function() {jQuery('#login_success').modal('hide');},2000);
                    }
                    else{
                        jQuery('#login_success').modal('show');
                        jQuery('#success').html('<span class=\"sucess_close\">&nbsp;</span><span class=\"msg-success\">Error! Something went wrong.</span>');
                        window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 2000);
                    }
                }
            })
        }
    }
", View::POS_HEAD);

$this->registerJsFile('@web/js/event_detail.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
