<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use common\models\Vendoritem;
use common\models\Category;
use yii\grid\GridView;
?>
<!-- coniner start -->
<section id="inner_pages_white_back">
<div class="container paddng0">
<?php if(Yii::$app->params['CUSTOMER_ID']!='') { ?>
<!-- Events slider start -->
<?php require(__DIR__ . '/../product/events_slider.php'); ?>
<!-- Events slider end -->
<?php } ?>
<div class="breadcrumb_common">
<div class="bs-example">
<!-- <ul class="breadcrumb"> -->
<?php
$this->params['breadcrumbs'][] = ['label' => ucfirst($slug), 'url' => Yii::$app->homeUrl.'/Event details/'.$slug];
//$this->params['breadcrumbs'][] =$model['item_name'];
?>
<?= Breadcrumbs::widget([
'options' => ['class' => 'new breadcrumb'],
'homeLink' => [
'label' => Yii::t('yii', 'Home'),
'url' => Yii::$app->homeUrl,
],
'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]);
?>

<!-- </ul> -->
</div>
</div>
<div class="events_detials_common">
<div class="events_inner_contents_new">
<div class="col-md-10 padding0">
<div class="events_inner_descript">
<h3><?php echo $event_details[0]['event_name']; ?> <a class="edit_content" href="#" title="( Edit )" onclick="editevent(<?php echo $event_details[0]['event_id'];?>)">( Edit )</a></h3>
<p><?php echo date('d-m-Y',strtotime($event_details[0]['event_date'])); ?></p>
<label><?php echo $event_details[0]['event_type']; ?></label>
</div>
</div>
<div class="col-md-2 padding-left0">
<div class="select_butons">
<a href="#invitee" title="invitees" type="button" class="btn btn-warning">invitees</a>
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
$cust_id = Yii::$app->params['CUSTOMER_ID'];
/* Load level 1 category */
//$cat_exist = Category::loadcategoryevents();
$cat_exist = Yii::$app->db->createCommand('SELECT * FROM {{%category}} WHERE `category_level` = 0 and `category_allow_sale`="yes" and trash="Default" and category_level = 0
 	order by FIELD(category_name,"Venues","Invitations","Food & Beverages","Decor","Supplies","Entertainment","Services","Others","Say thank you")')->queryAll();

foreach ($cat_exist as $key => $value1) {
	$cat_list1 = Yii::$app->db->createCommand('SELECT wvi.item_id FROM `whitebook_vendor_item` as wvi INNER JOIN whitebook_event_item_link as wei
ON wvi.item_id = wei.item_id  WHERE wvi.item_status = "Active" AND wvi.trash="Default" AND wvi.item_for_sale="Yes" AND wvi.type_id="2" and wei.trash="default" and wvi.category_id ='.$value1['category_id'].' and wei.event_id = '.$event_details[0]['event_id'].'')->queryAll();
?>
<div class="panel panel-default">
<div class="panel-heading" role="tab" id="heading<?= $key ?>">
<h4 class="panel-title">
<?php if($value1['slug']=='say-thank-you'){ ?>
<a data-toggle="collapse" id="description_click" data-parent="#accordion" href="#collapse<?= $key ?>" aria-expanded="false" aria-controls="collapse<?= $key ?>" class="collapsed">
<?php echo 'Say'.' "Thank You" '.' - '.'<span id="item_count">' .count($cat_list1). '</span>'; ?>
<span class="glyphicon glyphicon-menu-right text-align pull-right"></span></a>
<?php } else {  ?>
<a data-toggle="collapse" id="description_click" data-parent="#accordion" href="#collapse<?= $key ?>" aria-expanded="false" aria-controls="collapse<?= $key ?>" class="collapsed">
<?php echo $value1['category_name'].' - '.'<span id="item_count">' .count($cat_list1). '</span>'; ?>
<span class="glyphicon glyphicon-menu-right text-align pull-right"></span></a>
<?php } ?>
</h4>
</div>
<div id="collapse<?= $key ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?= $key ?>" aria-expanded="false">
<div class="panel-body">
<div class="events_inner_listing_new">
<div class="events_listing">
<ul>
<?php //$cat_exist1[] = array_push($cat_exist,$load_cat['category_id']);
$cat_list = Yii::$app->db->createCommand('SELECT DISTINCT wvi.item_id FROM `whitebook_vendor_item` as wvi INNER JOIN whitebook_event_item_link as wei
ON wvi.item_id = wei.item_id and wei.trash="default" and wvi.category_id ='.$value1['category_id'].' and wei.event_id = '.$event_details[0]['event_id'].'')->queryAll();
//print_r($cat_list);die;
if(!empty($cat_list))
{
foreach ($cat_list as $key => $cat_list_value) {
$imageData[] = Yii::$app->db->createCommand('select wvi.item_id, wei.link_id, wi.image_path, wvi.item_price_per_unit, wvi.item_name,wvi.slug, wvi.child_category, wvi.item_id FROM whitebook_vendor_item as wvi
LEFT JOIN whitebook_image as wi ON wvi.item_id = wi.item_id
LEFT JOIN whitebook_vendor as wv ON wv.vendor_id = wvi.vendor_id
LEFT JOIN whitebook_category as wc ON wc.category_id = wvi.child_category
LEFT JOIN whitebook_event_item_link as wei ON wei.item_id = wvi.item_id
WHERE wvi.trash="Default" and wvi.item_approved="Yes" and wvi.item_status="Active" and wvi.type_id="2"
and wvi.item_for_sale="Yes" AND wi.module_type="vendor_item" AND wei.event_id='.$event_details[0]['event_id'].' AND wvi.item_id='.$cat_list_value['item_id'].' Group By wvi.item_id limit 5')->queryOne();
}

if(!empty($imageData))
{
foreach ($imageData as $key => $value) {
if($value['image_path'] !="")  {
?>
<li>
<div class="events_items">
<div class="events_images">
<div class="hover_events">
<div class="event_delete_icons"><a href="javascript:void(0)" id="<?= $value['link_id']; ?>" onclick="deleteeventitem('<?= $value['link_id']; ?>','<?= $value1['category_name']; ?>','<?= $value1['category_id']; ?>','<?= $event_details[0]["event_id"]; ?>',this.id)" title="Delete"></a></div>
<?php $k=array();
foreach($customer_events_list as $l){
$k[]=$l['item_id'];
}
$result=array_search($value['item_id'],$k);
if (is_numeric ($result)) { ?>
<div class="faver_icons faverited_icons"> <?php } else { ?>
<div class="faver_icons">
<?php }?>
<a  href="javascript:;" role="button" id="<?php echo $value['item_id']; ?>"  class="add_to_favourite" name="add_to_favourite" title="<?php echo Yii::t('frontend','ADD_FAV');?>"></a></div>
</div>
<a href="<?php echo Yii::$app->homeUrl;?>/product/<?php echo $value['slug'];?>" title="" ><?= Html::img(Yii::getAlias("@vendor_item_images_210/").$value['image_path'],['class'=>'item-img', 'style'=>'width:210px; height:208px;']); ?></a>
</div>
<div class="events_descrip">
<a href="<?php echo Yii::$app->homeUrl;?>/product/<?php echo $value['slug'];?>" title=""><?php //$value['vendor_name']  ?></a>
<h3><?= $value['item_name']  ?></h3>
<p><? if($value['item_price_per_unit'] !='') {echo $value['item_price_per_unit'].'.00 KD'; }else echo '-';?></p>
</div>
</div>
</li>
<?php } }  $imageData=array();
}
?>
</ul>
<?php   } ?>
<div class="events_brows_buttons_common">
<div class="margin_0_auto">
<a href="<?= Url::toRoute('/products'); ?><?= '/'.$value1['slug'];?>" class="btn btn-danger">BROWSE THE CATEGORY</a>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php  }
?>
<!-- heading seven end -->

<div class="invates_common" id="invitee">
<h4>Invitees</h4>
<p>Invite your friends, relatives for this event</p>
<div class="invite_error" style="color:red;display:none;">Email already exist with this event.</div>
<div class="add_detials_form">
<div data-example-id="basic-forms" class="bs-example">
<form>
<div class="col-md-4 padding-right0">

<div class="form-group">
<input type="hidden" placeholder="id" name="invitees_id" id="invitees_id" class="form-control">
<input type="text" placeholder="Name" name="invitees_name" id="invitees_name" class="form-control">
</div>
</div>

<div class="col-md-4">
<div class="form-group">

<input type="email" placeholder="Email" name="invitees_email" id="invitees_email" class="form-control">
</div>
</div>
<div class="col-md-3">
<div class="form-group">

<input type="phone" placeholder="Phone" name="invitees_phone" id="invitees_phone" class="form-control">
</div>
</div>
<div class="col-md-1 padding0">
<div class="add_events_new">
<input type="button" class="btn btn-default" id="submit" value="Add" onClick="addinvitees()">
</div>
</div>
</form>
</div>
</div>
<div class="left_search_content">
<div data-example-id="basic-forms" class="bs-example">
<form>
<?php require(__DIR__ . '/_search.php'); ?>
<div class="col-md-8 padding0">
<div class="col-md-3 pull-left text-left padding0 desktop-hide">
<div class="input-group">
<div id="navigation-bar">
<form id="search" action="#" method="post">
<div id="input3" class="right_slider">
<input type="text" placeholder="Name/Phone/Email" id="inviteesearch1" class="form-control">
<span class="input-group-btn mobile-search-icon">
<button class="btn btn-default" type="button" onClick="Searchinvitee('<?php echo $event_details[0]['event_id'];?>')">Go!</button>
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
<a href="<?php echo Url::toRoute('/users/excel/'.$slug);?>" title="Export to Excel">Export to Excel</a>
<a href="#" title="Print" onclick="window.print()">Print</a>
</div>
</div>

</form>
</div>
</div>
<div class="add_contact_table">
<div class="table-responsive">
<?php \yii\widgets\Pjax::begin(['id'=>'itemtype']); ?>
<?= GridView::widget([
'dataProvider' => $dataProvider,
'columns' => [
['class' => 'yii\grid\SerialColumn'],
'name',
'email:email',
'phone_number',
['class' => 'yii\grid\ActionColumn',
'header'=>'Action',
'template' => '{delete}{update}',
'buttons' => [
'delete' => function ($url, $model) {
$url = '';
return  Html::a('<a href="javascript:void(0)" onclick="deleteinvitee('.$model->invitees_id.')"><span class="glyphicon glyphicon-trash"></span></a>', $url, [
'title' => Yii::t('app', 'Gallery'),
//'class'=>'btn btn-primary btn-xs',
]);
},
'update' => function ($url, $model) {
$url = '';
return  Html::a('<a href="javascript:void(0)"  onclick="updateinvitee('.$model->invitees_id.')"><span class="glyphicon glyphicon-pencil" style="margin-left:10px;"></span></a>', $url, [
'title' => Yii::t('app', 'Gallery'),
//'class'=>'btn btn-primary btn-xs',
]);
},
], ],
],
]); ?>
<?php \yii\widgets\Pjax::end(); ?>
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
</section>
<!-- continer end -->

<!-- end -->
<script type="text/javascript">

/* BEGIN Insert invitees for respective event */
function addinvitees()
{
var action = '';
var event_id = <?php echo $event_details[0]['event_id'];?>;
var event_name = "<?php echo $event_details[0]['event_name'];?>";
if(jQuery('#invitees_name').val() =='')
{
alert('Enter invitees name');
return false;
}
if(jQuery('#invitees_email').val() =='')
{
alert('Enter invitees email id');
return false;
}
else if(isEmail(jQuery('#invitees_email').val()) == false ){
alert('Enter valid email id');
return false;
}
var act = '';
if(jQuery('#invitees_id').val() =='')
{
action = 'addinvitees';
act = 'added';
}
else{
action = 'updateinvitees';
act = 'updated';
}

//var path = "<?php echo Url::current(['/eventinvitees/"+action+"']); ?> ";
var path = "<?php echo Yii::$app->urlManager->createAbsoluteUrl('eventinvitees/"+action+"'); ?>";

alert(path);
jQuery.ajax({
type :'POST',
url:path,
data: {invitees_id: jQuery('#invitees_id').val(), event_id:event_id,name: jQuery('#invitees_name').val(),email:jQuery('#invitees_email').val(),phone_number:jQuery('#invitees_phone').val(),event_name:event_name},
success:function(data)
{

if(data==2)
{
jQuery('.invite_error').show();
}
else
{
jQuery('#login_success').modal('show');
jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success">Success! Invitee '+act+' successfully!</span>');
window.setTimeout(function(){location.reload()},2000)
}
// jQuery.pjax.reload({container:'#itemtype'});
}
});
}

function deleteinvitee(invitee_id){
var pathUrl = "<?php echo Yii::$app->urlManager->createAbsoluteUrl('/eventinvitees/delete'); ?>?id="+invitee_id;
var r = confirm("Are you sure want to delete this invitee?");
if (r == true) {
jQuery.ajax({
url: pathUrl,
type : 'POST',
success : function()
{
location.reload(true);
}

});
return false;
}
return false;
}

function updateinvitee(invitee_id)
{
var pathUrl = "<?php echo Yii::$app->urlManager->createAbsoluteUrl('/eventinvitees/inviteedetails'); ?>";
jQuery.ajax({
url: pathUrl,
type : 'POST',
data :{id:invitee_id},
dataType:'JSON',
success : function(data)
{
jQuery('#invitees_id').val(data.invitees_id);
jQuery('#invitees_name').val(data.name);
jQuery('#invitees_email').val(data.email);
jQuery('#invitees_phone').val(data.phone_number);
jQuery('#submit').val('Update');
}

});
return false;
}
/* BEGIN EDIT EVENT */
function editevent(event_id)
{
jQuery.ajax({
type:'POST',
url:"<?= Url::toRoute('/product/eventdetails'); ?>",
data:{'event_id':event_id},
//dataType:'JSON',
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
jQuery("#collapse0").attr('aria-expanded', 'true');
jQuery("#collapse0").attr('class', 'panel-collapse collapse in');
//jQuery('#accordion .panel-default:last-child').css({background:"red"});
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

var r = confirm("Are you sure delete this item from "+category_name+"?");
if (r == true) {
jQuery.ajax({
url:'<?php echo Url::to(['/users/deleteeventitem']); ?>',
type:'POST',
data:{'item_link_id':item_link_id,'category_id':category_id,'event_id':event_id},
success:function(data)
{
if(data!=-1)
{
jQuery('#'+tis).parents('.panel-default').find('span#item_count').html(data);
jQuery('#'+tis).parents('li').remove();
jQuery('#login_success').modal('show');
jQuery('#login_success #success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">Success! Item removed from the '+category_name+'.</span>');
window.setTimeout(function() {jQuery('#login_success').modal('hide');},2000);
}
else{
jQuery('#login_success').modal('show');
jQuery('#success').html('<span class="sucess_close">&nbsp;</span><span class="msg-success" style="margin-top: 5px; width: 320px; float: left; text-align: left;">Error! Something went wrong.</span>');
window.setTimeout(function() {jQuery('#login_success').modal('hide');}, 2000);
}
}
})
}
}
</script>
