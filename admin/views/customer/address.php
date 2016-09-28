<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\grid\Column;
use yii\grid\DataColumn;
use yii\base;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SearchCustomer */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customer address';
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="customer-address">

<table class="table table-bordered">
	<tr>
		<td width="20%">Customer name</td>
		<td><?= $model->customer_name.' '.$model->customer_last_name ?></td>
	</tr>
	<tr>
		<td width="20%">Customer email</td>
		<td><?= $model->customer_email.' '.$model->customer_last_name ?></td>
	</tr>
</table>

<h2>Address list</h2>

<div class="row">
<?php foreach ($addresses as $address) { ?>
	<div class="col-lg-4">
		<div class="address_box">
			
			<a data-id="<?= $address['address_id'] ?>" class="address_delete pull-right">
				<i class="glyphicon glyphicon-trash"></i>
			</a>

			<?= $address['address_name']?nl2br($address['address_name']).'<br />':'' ?>

            <?= $address['address_data']?nl2br($address['address_data']).'<br />':'' ?>
			
			<?= $address['location']?$address['location'].'<br />':'' ?>
			
			<?= $address['city_name']?$address['city_name'].'<br />':'' ?>
			
			<?php if($address['questions']) { ?>
			<h4>Questions</h4>
			<ul>
			<?php foreach ($address['questions'] as $row) { ?>
				<li>
					<b><?= $row['question'] ?></b>
					<p><?= $row['response_text'] ?></p>
				</li>
			<?php } ?>
			<?php } ?>
			</ul>
		</div>
	</div>
<?php } ?>
</div>

<div class="clearfix"></div>

<hr />

<center>
<a class="btn btn-primary" data-toggle="modal" data-target="#modal_create_address">
	Add new address
</a>
</center>

</div>

<div class="modal fade" id="modal_create_address">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Add new address</h4>
      </div>
      <div class="modal-body" style="background: white;">

			<?php $form = ActiveForm::begin(); ?>

            <?= $form->field($customer_address_modal, 'address_name'); ?>

			<?= $form->field($customer_address_modal, 'address_type_id')->dropDownList($addresstype, ['prompt'=>'Select...']); ?>

			<div class="question_wrapper">
				<!-- question will go here -->
			</div>

			<?= $form->field($customer_address_modal, 'country_id')->dropDownList($country, ['prompt'=>'Select...']); ?>

			<?= $form->field($customer_address_modal, 'city_id')->dropDownList([], ['prompt'=>'Select...']); ?>

			<?= $form->field($customer_address_modal, 'area_id')->dropDownList([], ['prompt'=>'Select...']); ?>

			<div class="form-group">
				<?= $form->field($customer_address_modal, 'address_data',['template' => "{label}<div class='controls1'>{input}</div> {hint} {error}"
				])->textArea(['rows' => 6]) ?>
			</div>

			<?php ActiveForm::end(); ?>

	  <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" onclick="$('#modal_create_address form').submit();" class="btn btn-submit-address btn-primary">Submit</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php 

$this->registerJs("
    $(function (){

    	$('.address_delete').click(function(){
    		var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
            var path = '".Url::to(['/customer/address_delete'])."';

            var address_id = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: path, //url to be called
                data: { address_id: address_id, _csrf : csrfToken}, //data to be send
                success: function( data ) {
                     $('#customeraddress-city_id').html(data);
                }
            });

            $(this).parent().parent().remove();
    	});

    	$('#customeraddress-address_type_id').change(function (){
            var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
            var address_type_id = $('#customeraddress-address_type_id').val();
            var path = '".Url::to(['/customer/questions'])."';
            
            $.ajax({
                type: 'POST',
                url: path, //url to be called
                data: { address_type_id: address_type_id ,_csrf : csrfToken}, //data to be send
                success: function( data ) {
                     $('.question_wrapper').html(data);
                }
            });
        });

        $('#customeraddress-country_id').change(function (){
            var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
            var country_id = $('#customeraddress-country_id').val();
            var path = '".Url::to(['/location/city'])."';
            
            $.ajax({
                type: 'POST',
                url: path, //url to be called
                data: { country_id: country_id ,_csrf : csrfToken}, //data to be send
                success: function( data ) {
                     $('#customeraddress-city_id').html(data);
                }
            });
        });
    
        $('#customeraddress-city_id').change(function (){
            var csrfToken = $('meta[name=\"csrf-token\"]').attr('content');
            var city_id = $('#customeraddress-city_id').val();
            var path = '".Url::to(['/location/area'])."';
            
            $.ajax({
                type: 'POST',
                url: path, //url to be called
                data: { city_id: city_id ,_csrf : csrfToken}, //data to be send
                success: function( data ) {
                     $('#customeraddress-area_id').html(data);
                }
            });
         });
    });
");

    