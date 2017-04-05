var addon_menu_count = $('#addon_menu_count').val();
var menu_count = $('#menu_count').val();

var imagedata = $('#imagedata').val();
var img  = $('#img').val();
var action  = $('#action').val();
var guideimagedata = $('#guideimagedata').val();
var img1  = $('#img1').val();
var action1 = $('#action1').val();
var isNewRecord = $('#isNewRecord').val();
var item_status = $('#item_status').val();
var item_id = $('#item_id').val();

var load_sub_category_url = $('#load_sub_category_url').val();
var load_child_category_url = $('#load_child_category_url').val();

var image_delete_url = $('#image_delete_url').val();
var remove_question_url = $('#remove_question_url').val();

var render_question_url = $('#render_question_url').val();
var item_name_check_url = $('#item_name_check_url').val();
var image_order_url = $('#image_order_url').val();

var csrfToken = $('meta[name="csrf-token"]').attr('content');

$('#option').hide();

$('.vendoritemquestion-question_answer_type').on('change',function (){

	var type = $(this).val();

	if(type =='selection')
	{
		$(this).next('.price_val').remove();
		var j = $(this).attr('id').replace(/vendoritemquestion-question_answer_type/, '');
		$('#option').show();

		$(this).after('<div class="selection"><input type="text" class="form-control" name="VendorItemQuestion['+j+'][text][0][]" placeholder="Question" id="question" style="width:50%;float:left;"><input type="text" class="form-control" name="VendorItemQuestion['+j+'][price][0][]" placeholder="Price (Optional)" id="price" style="width:40%;float:left;"><input type="button" class="add_question" id=\add_question'+j+'" data-option-count="1" name="Addss" value="Add Selection"></div>');
	
	} else if(type =='image' ||  type =='text') {

		$(this).next('.selection').remove();
		$(this).next('.price_val').remove();
		var j = $(this).attr('id').replace(/vendoritemquestion-question_answer_type/, '');
		$('#option').show();

		$(this).after('<div class="price_val"><input type="text" class="form-control" name="VendorItemQuestion['+j+'][price][]" placeholder="Price (Optional)" id="price" style="width:40%;float:left;"></div>');
	}

	// Add selection for questions //
});

var p = 1;

$('.add_question').on('click',function(){
	var j = $(this).attr('id').replace(/add_question/, '');
	var p = $(this).attr('data-option-count');
	$(this).before('<div class="selection"><input type="text" class="form-control" name="VendorItemQuestion['+j+'][text]['+p+'][]" placeholder="Question" id="question" style="width:50%;float:left;"><input type="text" class="form-control" name="VendorItemQuestion['+j+'][price]['+p+'][]" placeholder="Price (Optional)" id="price" style="width:45%;float:left;"></div>');p++;
	$(this).attr('data-option-count',p);
});

function deletePhoto(image_id, loc){

	var path = image_delete_url;
    
    $.ajax({
        type: 'POST',
        url: path,
        data: { id: image_id ,_csrf : csrfToken, loc : loc},
        success: function( data ) {
			if(data == 'Deleted')
			{
				$('img#'+image_id).parent().remove();
			}
			return false;
         }
    });
}

function deleteAddress(d,question_id) {
	if(question_id != '')
	{
		if (confirm('Are you sure want to delete?')) {
			$('#'+d).remove();
			var path = remove_question_url;
        
	        $.ajax({
		        type: 'POST',
		        url: path,
		        data: { question_id: question_id ,_csrf : csrfToken},
		        success: function( data ) {
		             alert(data);
		        }
	        });

	        return false;
		}
		return false;
	}
}

var ck_item_description = '';
var ck_additional_info = '';
var ck_price_description = '';
var ck_customization_description = '';
var ck_item_description_ar = '';
var ck_additional_info_ar = '';
var ck_price_description_ar = '';
var ck_customization_description_ar = '';
 
$(function() {

	$config = {};
	$config.allowedContent = true;

	if($('#vendoritem-item_description').length > 0) {
		ck_item_description = CKEDITOR.replace('vendoritem-item_description', $config);	
	}
	
	if($('#vendoritem-item_additional_info').length > 0) {
		ck_additional_info = CKEDITOR.replace('vendoritem-item_additional_info', $config);
	}

	if($('#vendoritem-item_price_description').length > 0) {
		ck_price_description = CKEDITOR.replace('vendoritem-item_price_description', $config);
	}

	if($('#vendoritem-item_customization_description').length > 0) {
		ck_customization_description = CKEDITOR.replace('vendoritem-item_customization_description', $config);
	}

	if($('#vendoritem-item_description_ar').length > 0) {
		ck_item_description_ar = CKEDITOR.replace('vendoritem-item_description_ar', $config);
	}

	if($('#vendoritem-item_additional_info_ar').length > 0) {
		ck_additional_info_ar = CKEDITOR.replace('vendoritem-item_additional_info_ar', $config);
	}

	if($('#vendoritem-item_price_description_ar').length > 0) {
		ck_price_description_ar = CKEDITOR.replace('vendoritem-item_price_description_ar', $config);
	}

	if($('#vendoritem-item_customization_description_ar').length > 0) {
		ck_customization_description_ar = CKEDITOR.replace('vendoritem-item_customization_description_ar', $config);
	}
});

// Question and answer begin
/* 	BEGIN Themes and groups multiselect widget */
$(function(){

	if($('#vendoritem-themes').length > 0) {
		$('#vendoritem-themes').multiselect({
			'enableFiltering': true,
	        'filterPlaceholder': 'Search for something...'
	    });	
	}
 	
	if($('#vendoritem-groups').length > 0) {
	  	$('#vendoritem-groups').multiselect({
			'enableFiltering': true,
	        'filterPlaceholder': 'Search for something...'
	    });
	}
});

/* Price chart for item */
var j= 2;

function addPrice(tis)
{
	$(tis).before('<div class="controls'+j+'"><input type="text" id="vendoritem-item_from" class="form-control from_range_'+j+'" name="vendoritem-item_price[from][]" multiple="multiple" Placeholder="From Quantity"><input type="text" id="vendoritem-item_to" class="form-control to_range_'+j+'" name="vendoritem-item_price[to][]" multiple = "multiple" Placeholder="To Quantity"><input type="text" id="item_price_per_unit" class="form-control price_kd'+j+'" name="vendoritem-item_price[price][]" multiple = "multiple" Placeholder="Price">KD<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onClick="removePrice(this)" /></div>');
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

/* Price chart for item */

/* END Themes and groups multiselect widget */
/* BEGIN bootstrap file input widget for image preview */
$(document).on('ready', function() {
	
		/* BEGIN SORT code for item and guide images */
		var path = image_order_url;
		$(".file-preview-thumbnails").sortable({
			items:'> div.file-preview-initial',
        stop : function(event, ui){
		var newArray = $(this).sortable("toArray",{key:'s'});
		sort = [];
		var id = newArray.filter(function(v){return v!==''});
		
		for(var p=0;p<id.length;p++){
			sort.push($('div#'+id[p]+'').attr('data-key'));
		}

		$.ajax({
	        type: 'POST',
	        url: path,
	        data: { id: id,sort:sort,_csrf : csrfToken},
	        success: function( data ) {
	            // fine
	        }
		});
	  }
	});
	/* END SORT code for item and guide images */

	$(".file-preview-initial > img").each(function(){
		$(this).parent().attr('data-key',$(this).attr('data-key'));
	});
});

// single question view
function questionView(q_id,tis){
	
	var check = $('.show_ques'+q_id).html();
	
	if(check==''){
		var path = render_question_url;
		$.ajax({
			type : 'POST',
			url :  path,
			data: { q_id: q_id ,_csrf : csrfToken},
	        success: function( data ) {
		        $('.show_ques'+q_id).html(data);
		        $(tis).toggleClass('expanded');
		        return false;
	        }
		});
	}else{
		$('.show_ques'+q_id).toggle();
		$(this).toggleClass('expanded');
	}
}

/** 
 * Image crop and upload 
 */
$(function() {
 	
 	var croped_image_upload_url = $('#croped_image_upload_url').val();
    var image_count = $('#image_count').val();

    if($('.image-editor').length > 0) {

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

$(function() {

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

		//remove sub and child list 
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
	$html += '					<input placeholder="Name" name="menu_item['+menu_count+'][menu_name]" value="" class="txt_menu_name form-control" />';
	$html += '				</td class="required">';
	$html += '				<td>';
	$html += '					<input placeholder="Name - Arabic" name="menu_item['+menu_count+'][menu_name_ar]" value="" class="txt_menu_name_ar form-control" />';
	$html += '				</td>';
	$html += '				<td>';
	$html += '					<input placeholder="Min. Qty" name="menu_item['+menu_count+'][min_quantity]" value="" class="txt_min_quantity form-control" />';
	$html += '				</td>';
	$html += '				<td>';
	$html += '					<input placeholder="Max. Qty" name="menu_item['+menu_count+'][max_quantity]" value="" class="txt_max_quantity form-control" />';
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
	$html += '		<input placeholder="Name" name="menu_item['+menu_count+'][menu_item_name]" value="" class="txt_menu_item_name form-control" /></td>';
	$html += '	<td class="required">';
	$html += '		<input placeholder="Name - Arabic" name="menu_item['+menu_count+'][menu_item_name_ar]" value="" class="txt_menu_item_name_ar form-control" /></td>';
	
	$html += '	<td>';
	$html += '		<input placeholder="Hint" name="menu_item['+menu_count+'][hint]" value="" class="form-control txt_hint" />';
	$html += '	</td>';
	
	$html += '	<td>';
	$html += '		<input placeholder="Hint - Ar" name="menu_item['+menu_count+'][hint_ar]" value="" class="form-control txt_hint_ar" />';
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
	$html += '					<input placeholder="Name" name="addon_menu_item['+addon_menu_count+'][menu_name]" value="" class="txt_menu_name form-control" />';
	$html += '				</td>';
	$html += '				<td>';
	$html += '					<input placeholder="Name - Arabic" name="addon_menu_item['+addon_menu_count+'][menu_name_ar]" value="" class="txt_menu_name_ar form-control" />';
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
	$html += '		<input placeholder="Name" name="addon_menu_item['+addon_menu_count+'][menu_item_name]" value="" class="txt_menu_item_name form-control" /></td>';
	$html += '	<td>';
	$html += '		<input placeholder="Name - Arabic" name="addon_menu_item['+addon_menu_count+'][menu_item_name_ar]" value="" class="txt_menu_item_name_ar form-control" /></td>';
	
	$html += '	<td>';
	$html += '		<input placeholder="Price" name="addon_menu_item['+addon_menu_count+'][price]" value="" class="txt_price form-control" />';
	$html += '	</td>';
	
	$html += '	<td>';
	$html += '		<input placeholder="Hint" name="addon_menu_item['+addon_menu_count+'][hint]" value="" class="txt_hint form-control" />';
	$html += '	</td>';
	
	$html += '	<td>';
	$html += '		<input placeholder="Hint - Ar" name="addon_menu_item['+addon_menu_count+'][hint_ar]" value="" class="txt_hint_ar form-control" />';
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
