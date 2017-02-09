var addon_menu_count = $('#addon_menu_count').val();
var menu_count = $('#menu_count').val();

var count_q = $('#count_q').val();
var appImageUrl = $('#appImageUrl').val();
var image_order_url = $('#image_order_url').val();
var deletequestionoptions_url = $('#deletequestionoptions_url').val();
var salesguideimage_url = $('#salesguideimage_url').val();
var request_create = $('#request_create').val();
var isNewRecord = $('#isNewRecord').val();
var item_for_sale = $('#item_for_sale').val();
var item_status = $('#item_status').val();
var item_id = $('#item_id').val();
var item_name_check = $('#item_name_check').val();
var add_question_url = $('#add_question_url').val();
var guideimage_url = $('#guideimage_url').val();
var exist_question = $('#exist_question').val();
var removequestion_url = $('#removequestion_url').val();
var vendorcategory_url = $('#vendorcategory_url').val();
var loadsubcategory_url = $('#loadsubcategory_url').val();
var loadchildcategory_url = $('#loadchildcategory_url').val();
var renderquestion_url = $('#renderquestion_url').val();

var csrfToken = $('meta[name="csrf-token"]').attr("content");

$(function (){

 	/* For themes and groups list checkbox alignment*/
 	$(".themelists:last-child").css({"clear" : "both","float" :"inherit"});
 	/* For themes and groups list checkbox alignment*/

	$('#option').hide();
});

$(function() {

	$config = {};
	$config.allowedContent = true;

	if($('#vendoritem-item_description').length > 0) {
		CKEDITOR.replace('vendoritem-item_description', $config);	
	}
	
	if($('#vendoritem-item_additional_info').length > 0) {
		CKEDITOR.replace('vendoritem-item_additional_info', $config);
	}

	if($('#vendoritem-item_price_description').length > 0) {
		CKEDITOR.replace('vendoritem-item_price_description', $config);
	}

	if($('#vendoritem-item_customization_description').length > 0) {
		CKEDITOR.replace('vendoritem-item_customization_description', $config);
	}

	if($('#vendoritem-item_description_ar').length > 0) {
		CKEDITOR.replace('vendoritem-item_description_ar', $config);
	}

	if($('#vendoritem-item_additional_info_ar').length > 0) {
		CKEDITOR.replace('vendoritem-item_additional_info_ar', $config);
	}

	if($('#vendoritem-item_price_description_ar').length > 0) {
		CKEDITOR.replace('vendoritem-item_price_description_ar', $config);
	}

	if($('#vendoritem-item_customization_description_ar').length > 0) {
		CKEDITOR.replace('vendoritem-item_customization_description_ar', $config);
	}
});


// Add more for pricing 
$(function(){

	$('.custom_description').hide();
	$('.custom_description_ar').hide();
	if (item_for_sale == 0) {
		$('.guide_image').show();
	} else {
		$('.guide_image').hide();
	}
	$('.mandatory').show();

	$('#vendoritem-item_for_sale').click(function()
	{
		if($(this).is(':checked'))
		{
			$('.custom_description').hide();
			$('.custom_description_ar').hide();
			$('.guide_image').hide();
			$('.mandatory').show();
		}
		else
		{
			$('.mandatory').hide();
			$('.custom_description').show();
			$('.custom_description_ar').show();
			$('.guide_image').show();
		}
	});

	if(!isNewRecord) {
		if($("#vendoritem-item_for_sale").prop('checked') == true){
			$('.custom_description').hide();
			$('.custom_description_ar').hide();
			$('.guide_image').hide();
		}
		else
		{
			$('.custom_description').show();
			$('.custom_description_ar').show();
			$('.guide_image').show();
		}
	}
});

var j= 2;

function addPrice(tis)
{
	$(tis).before('<div class="controls'+j+'"><input type="text" id="vendoritem-item_from" class="form-control from_range_'+j+'" name="vendoritem-item_price[from][]" multiple = "multiple" Placeholder="From Quantity" /><input type="text" id="vendoritem-item_to" class="form-control to_range_'+j+'" name="vendoritem-item_price[to][]" multiple = "multiple" Placeholder="To Quantity" /><input type="text" id="item_price_per_unit" class="form-control price_kd'+j+'" name="vendoritem-item_price[price][]" multiple = "multiple" Placeholder="Price">KD<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onClick="removePrice(this)" /></div>');
	j++;
}

function removePrice(tis)
{
	var r = confirm("Are you sure want to delete?");
	if (r == true) {
		$(tis).parent().remove();
	    return false;
 	}
}

var csrfToken = $('meta[name="csrf-token"]').attr("content");

$(document).ready(function() {

	$('.check:button').toggle(function(){
	    $('input:checkbox').attr('checked','checked');
        $(this).val('Uncheck all');
	}, function(){
		$('input:checkbox').removeAttr('checked');
		$(this).val('Check all');
	});

 });

$(document).ready(function () {
  $("#vendoritem-item_amount_in_stock").keypress(function (e) {
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          $(".field-vendoritem-item_amount_in_stock").find('.help-block').html('Item number of stock must be an integer.').animate({ color: "#a94442" }).show().fadeOut(2000);
         return false;
    }
   });
});

$(document).ready(function () {
  $("#vendoritem-item_how_long_to_make").keypress(function (e) {
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          $(".field-vendoritem-item_how_long_to_make").find('.help-block').html('No of days delivery must be an integer.').animate({ color: "#a94442" }).show().fadeOut(2000);
         return false;
    }
   });
});

$(document).ready(function () {
  $("#vendoritem-item_default_capacity").keypress(function (e) {
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          $(".field-vendoritem-item_default_capacity").find('.help-block').html('Item default capacity must be an integer.').animate({ color: "#a94442" }).show().fadeOut(2000);
         return false;
    }
   });
});

$(document).ready(function () {
  $("#vendoritem-item_minimum_quantity_to_order").keypress(function (e) {
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          $(".field-vendoritem-item_minimum_quantity_to_order").find('.help-block').html('Item minimum quantity to order must be an integer.').animate({ color: "#a94442" }).show().fadeOut(2000);
         return false;
    }
   });
});

$(document).ready(function () {
  $("#vendoritem-item_price_per_unit").keypress(function (e) {
     if (e.which != 46 && e.which != 45 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          $(".field-vendoritem-item_price_per_unit").find('.help-block').html('Item price per unit must be a number.').animate({ color: "#a94442" }).show().fadeOut(2000);
         return false;
    }
   });
});

//add categort 
$('.btn-add-category').click(function(){
	
	$category_id = $('#category_id').val();

	if($category_id.length == 0) {
		return false;
	}

	$html  = '<tr>';
	$html += '	<td>';
	$html += 		$('#category_id option:selected').html();
	$html += '		<input value="' + $category_id + '" name="category[]" type="hidden" />';
	$html += '	</td>';
	$html += '	<td>';
	$html += '		<button class="btn btn-danger" type="button">';
	$html += '			<i class="glyphicon glyphicon-trash"></i>';
	$html += '		</button>';
	$html += '	</td>';
	$html += '</tr>';

	$('.table-category-list tbody').append($html);

	$(".field-category-list").removeClass('has-error');
	$(".field-category-list").find('.help-block').html('');
});

$(document).delegate('.table-category-list .btn-danger','click', function(){
	$(this).parent().parent().remove();
});

/** 
 * Image crop and upload 
 */
 $(function() {

 	if($('.image-editor').length > 0) {

		var croped_image_upload_url = $('#croped_image_upload_url').val();
	    var image_count = $('#image_count').val();
	    
	    $('.image-editor').cropit({ imageBackground: true });

	    $('.btn-crop-upload').click(function(){

	    	//remove old warning 
	    	$('.alert-image-size').remove();

	    	var imageData = $('.image-editor').cropit('export');

			if(!imageData) {
				$html  = '<div class="alert alert-warning alert-image-size">';
				$html += '	Please upload valid image with size of atlease 530px x 530px!';
				$html += '	<button class="close" data-dismiss="alert"></button>';
				$html += '</div>';

				$('.file-block').after($html);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
				
				return false;
			}

			$(this).attr('disabled', 'disabled');
	    	$(this).html('Uploading...');

	    	//upload image 
	    	$.post(croped_image_upload_url, { image : imageData }, function(json) {

	    		var html = '<tr>';
				html +=	'<td>';
				html +=	'		<div class="vendor_image_preview">';
				html +=	'			<img src="' + json.image_url + '" />';
				html +=	'		</div>';
				html +=	'		<input type="hidden" name="images[' + image_count + '][image_path]" value="' + json.image + '" />';
				html +=	'	</td>';
				html +=	'	<td>';
				html +=	'		<input type="text" name="images[' + image_count + '][vendorimage_sort_order]" value="" />';
				html +=	'	</td>';
				html +=	'	<td>';
				html +=	'		<button class="btn btn-danger btn-delete-image">';
				html +=	'			<i class="fa fa-trash"></i>';
				html +=	'		</button>';
				html +=	'	</td>';
				html +=	'</tr>';

	    		$('.table-item-image tbody').append(html);

	    		$('.btn-crop-upload').html('Upload');
	    		$('.btn-crop-upload').removeAttr('disabled');

	    		image_count++;
	    	});
	    });

	    //delete image from item image table 
	    $(document).delegate('.table-item-image .btn-delete-image', 'click', function() {
	    	$(this).parents('tr').remove();
	    });
	}
});

/* -------------------- Theme -----------------------*/

$(document).delegate('.btn_theme_form_wrapper', 'click', function() {
	$('.theme_form_wrapper').removeClass('hidden');
	$(this).addClass('hidden');
});

$(document).delegate('.btn-add-theme-calcle', 'click', function() {
	$('.theme_form_wrapper').addClass('hidden');
	$('.btn_theme_form_wrapper').removeClass('hidden');
});

$(document).delegate('.btn-add-theme', 'click', function() {

	$theme_name = $('.theme_form_wrapper input[name="theme_name"]').val();
	$theme_name_ar = $('.theme_form_wrapper input[name="theme_name_ar"]').val();

	if($theme_name && $theme_name_ar) {

		$.post($('#add_theme_url').val(), { 'theme_name' : $theme_name, 'theme_name_ar' : $theme_name_ar } , function(json) {

			if(json.theme_id) {
				$html = '<label><input type="checkbox" name="VendorItem[themes][]" value="' + json.theme_id + '">' + $theme_name + '</label>';
				$('#vendoritem-themes').append($html);
				$('.btn_theme_form_wrapper').removeClass('hidden');
				$('.theme_form_wrapper').addClass('hidden');	
			}			
		});
	}
});

/* -------------------- Group -----------------------*/

$(document).delegate('.btn_group_form_wrapper', 'click', function() {
	$('.group_form_wrapper').removeClass('hidden');
	$(this).addClass('hidden');
});

$(document).delegate('.btn-add-group-calcle', 'click', function() {
	$('.group_form_wrapper').addClass('hidden');
	$('.btn_group_form_wrapper').removeClass('hidden');
});

$(document).delegate('.btn-add-group', 'click', function() {

	$group_name = $('.group_form_wrapper input[name="group_name"]').val();
	$group_name_ar = $('.group_form_wrapper input[name="group_name_ar"]').val();

	if($group_name && $group_name_ar) {

		$.post($('#add_group_url').val(), { 'group_name' : $group_name, 'group_name_ar' : $group_name_ar } , function(json) {

			if(json.group_id) {
				$html = '<label><input type="checkbox" name="VendorItem[groups][]" value="' + json.group_id + '">' + $group_name + '</label>';
				$('#vendoritem-groups').append($html);
				$('.btn_group_form_wrapper').removeClass('hidden');
				$('.group_form_wrapper').addClass('hidden');	
			}			
		});
	}
});

$(document).delegate('.txt-main-cat-search', 'keyup', function() {

	$q = $(this).val().toLowerCase();

	$('.main-category-list .checkbox').each(function() {

		$a = $(this).attr('data-name').toLowerCase();

		if($a.indexOf($q) == -1) {
			$(this).hide();
		}else{
			$(this).show();
		}
	});
});

$(document).delegate('.txt-sub-cat-search', 'keyup', function() {

	$q = $(this).val().toLowerCase();

	$('.sub-category-list .checkbox').each(function() {

		$a = $(this).attr('data-name').toLowerCase();

		if($a.indexOf($q) == -1) {
			$(this).hide();
		}else{
			$(this).show();
		}
	});
});

$(document).delegate('.txt-child-cat-search', 'keyup', function() {

	$q = $(this).val().toLowerCase();

	$('.child-category-list .checkbox').each(function() {

		$a = $(this).attr('data-name').toLowerCase();

		if($a.indexOf($q) == -1) {
			$(this).hide();
		}else{
			$(this).show();
		}
	});
});

$(function() {

	$(document).delegate('#sub_category_form', 'submit', function(e) {

		$.post($('#category_add_url').val(), $('#sub_category_form').serialize(), function(json) {
			
			$('.msg_wrapper').html('');

			if(json.errors) {

				$html  = '<div class="alert alert-warning">';
				$html += '<button class="close" data-dismiss="alert"></button>';
				
				$.each(json.errors, function(key, errors) 
				{
					$.each(errors, function(index, error)
					{
						$html += '<p>' + error + '</p>';	
					});					
				});

				$html += '</div>';

				$('#sub_category_modal .msg_wrapper').html($html);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}

			if(json.category_id) {

				$html  = '<div class="radio" data-name="'+json.category_name+'">';
				$html += '	<input type="radio" name="sub_category" value="'+json.category_id+'" id="sub_cat_'+json.category_id+'" />';
				$html += '	<label for="sub_cat_'+json.category_id+'">';
				$html += 		json.category_name;
				$html += '	</label>';
				$html += '</div>';

				$('.sub-category-list .chk_wrapper').append($html);

				$('.sub-category-list .chk_wrapper input:last-child').trigger('click');

				$('#sub_category_modal').modal('hide');
			}
		});

		e.preventDefault();
		e.stopPropagation();
	});


	$(document).delegate('#child_category_form', 'submit', function(e) {

		$('.msg_wrapper').html('');

		$.post($('#category_add_url').val(), $('#child_category_form').serialize(), function(json) {
			
			if(json.errors) {

				$html  = '<div class="alert alert-warning">';
				$html += '<button class="close" data-dismiss="alert"></button>';
				
				$.each(json.errors, function(key, errors) 
				{
					$.each(errors, function(index, error)
					{
						$html += '<p>' + error + '</p>';	
					});					
				});

				$html += '</div>';

				$('#child_category_modal .msg_wrapper').html($html);

				$('html, body').animate({ scrollTop: 0 }, 'slow');
			}

			if(json.success) {

				$html  = '<div class="radio" data-name="'+json.category_name+'">';
				$html += '	<input type="radio" name="child_category" value="'+json.category_id+'" id="child_cat_'+json.category_id+'" />';
				$html += '	<label for="child_cat_'+json.category_id+'">';
				$html += 		json.category_name;
				$html += '	</label>';
				$html += '</div>';

				$('.child-category-list .chk_wrapper').append($html);

				$('.child-category-list .chk_wrapper input:last-child').trigger('click');

				$('#child_category_modal').modal('hide');
			}
		});

		e.preventDefault();
		e.stopPropagation();
	});

	$(document).delegate('.main-category-list input', 'change', function() {

		//remove sub and child list 
		$('.sub-category-list .chk_wrapper').html('');
		$('.child-category-list .chk_wrapper').html('');

		$.post($('#category_list_url').val(), { parent_id : $(this).val() }, function(json) {
			
			$html = '';

			for(var i=0; i < json.categories.length; i++){
				$html += '<div class="radio" data-name="'+json.categories[i].category_name+'">';
				$html += '	<input type="radio" name="sub_category" value="'+json.categories[i].category_id+'" id="sub_cat_'+json.categories[i].category_id+'" />';
				$html += '	<label for="sub_cat_'+json.categories[i].category_id+'">';
				$html += 		json.categories[i].category_name;
				$html += '	</label>'; 
				$html += '</div>';
			}
		
			$('.sub-category-list .chk_wrapper').html($html);
		});
	});

	$(document).delegate('.sub-category-list input', 'change', function() {

		//remove child list 
		$('.child-category-list .chk_wrapper').html('');
		
		$.post($('#category_list_url').val(), { parent_id : $(this).val() }, function(json) {
			
			$html = '';

			for(var i=0; i < json.categories.length; i++){
				$html += '<div class="radio" data-name="'+json.categories[i].category_name+'">';
				$html += '	<input type="radio" name="child_category" value="'+json.categories[i].category_id+'" id="child_cat_'+json.categories[i].category_id+'" />';
				$html += '	<label for="child_cat_'+json.categories[i].category_id+'">';
				$html += 		json.categories[i].category_name;
				$html += '	</label>'; 
				$html += '</div>';
			}
		
			$('.child-category-list .chk_wrapper').html($html);
		});
	});

	$(document).delegate('.child-category-list input', 'change', function() {

		$html  = '<tr>';
		$html += '	<td>';
		$html += 		$('.main-category-list input:checked').parents('.radio').attr('data-name');
		$html += '		<input type="hidden" name="category[]" value="'+$('.main-category-list input:checked').val() + '" />';
		$html += '	</td>';
		$html += '	<td>';
		$html +=  		$('.sub-category-list input:checked').parents('.radio').attr('data-name');
		$html += '		<input type="hidden" name="category[]" value="'+$('.sub-category-list input:checked').val() + '" />';
		$html += '	</td>';
		$html += '	<td>';
		$html +=  		$('.child-category-list input:checked').parents('.radio').attr('data-name');
		$html += '		<input type="hidden" name="category[]" value="'+$('.child-category-list input:checked').val() + '" />';
		$html += '	</td>';
		$html += '	<td>';
		$html += '		<button class="btn btn-danger btn-remove-cat"><i class="fa fa-trash-o"></i></button>';
		$html += '	</td>';
		$html += '</tr>';

		$('.table-item-category-list tbody').append($html);
	});

	$(document).delegate('.btn-remove-cat', 'click', function() {
		$(this).parents('tr').remove();
	});

	$(document).delegate('.btn_sub_category_modal', 'click', function() {		
		
		$parent_id = $('.main-category-list input:checked').val();

		if($parent_id > 0) {
			$('#hdn_sub_cat_parent').val($parent_id);
			$('#sub_category_modal').modal('show');
		} else {
			alert('Please select main category.');
			return false;
		}		
	});

	$(document).delegate('.btn_child_category_modal', 'click', function() {		
		
		$parent_id = $('.sub-category-list input:checked').val();

		if($parent_id > 0) {
			$('#hdn_child_cat_parent').val($parent_id);
			$('#child_category_modal').modal('show');
		}else{
			alert('Please select sub category.');
			return false;
		}
	});
});

// ---------------------- menu -------------------------//

$(document).delegate('.btn-remove-menu', 'click', function(){
	$(this).parents('li').remove();
});

$(document).delegate('.btn-remove-menu-item', 'click', function(){
	$(this).parents('tr').remove();
});

$(document).delegate('.btn-add-menu', 'click', function(){

	$html  = '<li>';
	$html += '	<table class="table table-bordered">';
	$html += '		<thead>';
	$html += '			<tr>';
	$html += '				<th colspan="5" class="heading">Menu';
	$html += '					<button type="button" class="btn btn-danger btn-remove-menu">';
	$html += '						<i class="fa fa-trash-o"></i>';
	$html += '					</button>';
	$html += '				</th>';
	$html += '			</tr>';
	$html += '			<tr>';
	$html += '				<th>Name</th>';
	$html += '				<th>Name - Ar</th>';
	$html += '				<th>Min Qty</th>';
	$html += '				<th>Max Qty</th>';
	$html += '				<th>Qty Type</th>';
	$html += '			</tr>';
	$html += '		</thead>';
	$html += '		<tbody>';
	$html += '			<tr>';
	$html += '				<td class="required">';
	$html += '					<input placeholder="Name" name="menu_item['+menu_count+'][menu_name]" value="" class="form-control" />';
	$html += '				</td class="required">';
	$html += '				<td>';
	$html += '					<input placeholder="Name - Arabic" name="menu_item['+menu_count+'][menu_name_ar]" value="" class="form-control" />';
	$html += '				</td>';
	$html += '				<td>';
	$html += '					<input placeholder="Min. Qty" name="menu_item['+menu_count+'][min_quantity]" value="" class="form-control" />';
	$html += '				</td>';
	$html += '				<td>';
	$html += '					<input placeholder="Max. Qty" name="menu_item['+menu_count+'][max_quantity]" value="" class="form-control" />';
	$html += '				</td>';
	$html += '				<td>';
	$html += '					<select name="menu_item['+menu_count+'][quantity_type]" class="form-control">';
	$html += '						<option>selection</option>';
	$html += '						<option>checkbox</option>';
	$html += '					</select>';
	$html += '				</td>';
	$html += '			</tr>';
	$html += '		</tbody>';
	$html += '	</table>';

	$html += '	<table class="table table-bordered">';
	$html += '		<thead>';
	$html += '			<tr>';
	$html += '				<th colspan="5" class="heading">Menu Items</th>';
	$html += '			</tr>';
	$html += '			<tr>';
	$html += '				<th>Name</th>';
	$html += '				<th>Name - Ar</th>';
	$html += '				<th>Hint</th>';
	$html += '				<th>Hint - Ar</th>';
	$html += '				<th></th>';
	$html += '			</tr>';
	$html += '		</thead>';
	$html += '		<tbody>	';						
	$html += '		</tbody>';
	$html += '		<tfoot>';
	$html += '			<tr>';
	$html += '				<td colspan="5">';
	$html += '					<button type="button" class="btn btn-primary btn-add-menu-item">';
	$html += '						<i class="fa fa-plus"></i> Add Item';
	$html += '					</button>';
	$html += '				</td>';
	$html += '			</tr>';
	$html += '		</tfoot>';
	$html += '	</table>';
	$html += '</li>';

	$('#item_menu_list').append($html);

	menu_count++;
});

$(document).delegate('.btn-add-menu-item', 'click', function(){
	
	$html  = '<tr>';
	$html += '	<td class="required">';
	$html += '		<input placeholder="Name" name="menu_item['+menu_count+'][menu_item_name]" value="" class="form-control" /></td>';
	$html += '	<td class="required">';
	$html += '		<input placeholder="Name - Arabic" name="menu_item['+menu_count+'][menu_item_name_ar]" value="" class="form-control" /></td>';
	
	$html += '	<td>';
	$html += '		<input placeholder="Hint" name="menu_item['+menu_count+'][hint]" value="" class="form-control" />';
	$html += '	</td>';
	
	$html += '	<td>';
	$html += '		<input placeholder="Hint - Ar" name="menu_item['+menu_count+'][hint_ar]" value="" class="form-control" />';
	$html += '	</td>';
	
	$html += '	<td>';
	$html += '		<button type="button" class="btn btn-danger btn-remove-menu-item">';
	$html += '			<i class="fa fa-trash-o"></i>';
	$html += '		</button>';
	$html += '	</td>';
	$html += '</tr>';

	$(this).parents('table').find('tbody').append($html);

	menu_count++;
});

// ---------------------- addon menu -------------------------//

$(document).delegate('.btn-add-addon-menu', 'click', function(){

	$html  = '<li>';
	$html += '	<table class="table table-bordered">';
	$html += '		<thead>';
	$html += '			<tr>';
	$html += '				<th colspan="2" class="heading">Addon Menu';
	$html += '					<button type="button" class="btn btn-danger btn-remove-menu">';
	$html += '						<i class="fa fa-trash-o"></i>';
	$html += '					</button>';
	$html += '				</th>';
	$html += '			</tr>';
	$html += '			<tr>';
	$html += '				<th>Name</th>';
	$html += '				<th>Name - Ar</th>';
	$html += '			</tr>';
	$html += '		</thead>';
	$html += '		<tbody>';
	$html += '			<tr>';
	$html += '				<td>';
	$html += '					<input placeholder="Name" name="addon_menu_item['+addon_menu_count+'][menu_name]" value="" class="form-control" />';
	$html += '				</td>';
	$html += '				<td>';
	$html += '					<input placeholder="Name - Arabic" name="addon_menu_item['+addon_menu_count+'][menu_name_ar]" value="" class="form-control" />';
	$html += '				</td>';
	$html += '			</tr>';
	$html += '		</tbody>';
	$html += '	</table>';

	$html += '	<table class="table table-bordered">';
	$html += '		<thead>';
	$html += '			<tr>';
	$html += '				<th colspan="6" class="heading">Menu Items</th>';
	$html += '			</tr>';
	$html += '			<tr>';
	$html += '				<th>Name</th>';
	$html += '				<th>Name - Ar</th>';	
	$html += '				<th>Price</th>';
	$html += '				<th>Hint</th>';
	$html += '				<th>Hint - Ar</th>';
	$html += '				<th></th>';
	$html += '			</tr>';
	$html += '		</thead>';
	$html += '		<tbody>	';						
	$html += '		</tbody>';
	$html += '		<tfoot>';
	$html += '			<tr>';
	$html += '				<td colspan="6">';
	$html += '					<button type="button" class="btn btn-primary btn-add-addon-menu-item">';
	$html += '						<i class="fa fa-plus"></i> Add addon item';
	$html += '					</button>';
	$html += '				</td>';
	$html += '			</tr>';
	$html += '		</tfoot>';
	$html += '	</table>';
	$html += '</li>';

	$('#item_addon_menu_list').append($html);

	addon_menu_count++;
});

$(document).delegate('.btn-add-addon-menu-item', 'click', function(){
	
	$html  = '<tr>';
	$html += '	<td>';
	$html += '		<input placeholder="Name" name="addon_menu_item['+addon_menu_count+'][menu_item_name]" value="" class="form-control" /></td>';
	$html += '	<td>';
	$html += '		<input placeholder="Name - Arabic" name="addon_menu_item['+addon_menu_count+'][menu_item_name_ar]" value="" class="form-control" /></td>';
	
	$html += '	<td>';
	$html += '		<input placeholder="Price" name="addon_menu_item['+addon_menu_count+'][price]" value="" class="form-control" />';
	$html += '	</td>';
	
	$html += '	<td>';
	$html += '		<input placeholder="Hint" name="addon_menu_item['+addon_menu_count+'][hint]" value="" class="form-control" />';
	$html += '	</td>';
	
	$html += '	<td>';
	$html += '		<input placeholder="Hint - Ar" name="addon_menu_item['+addon_menu_count+'][hint_ar]" value="" class="form-control" />';
	$html += '	</td>';
	
	$html += '	<td>';
	$html += '		<button type="button" class="btn btn-danger btn-remove-menu-item">';
	$html += '			<i class="fa fa-trash-o"></i>';
	$html += '		</button>';
	$html += '	</td>';
	$html += '</tr>';

	$(this).parents('table').find('tbody').append($html);

	addon_menu_count++;
});
