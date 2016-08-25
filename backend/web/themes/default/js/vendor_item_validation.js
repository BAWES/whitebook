var imagedata = $('#imagedata').val();
var img  = $('#img').val();
var action  = $('#action').val();
var guideimagedata = $('#guideimagedata').val();
var img1  = $('#img1').val();
var action1 = $('#action1').val();
var isNewRecord = $('#isNewRecord').val();
var item_for_sale = $('#item_for_sale').val();
var item_status = $('#item_status').val();
var item_id = $('#item_id').val();

var load_sub_category_url = $('#load_sub_category_url').val();
var load_child_category_url = $('#load_child_category_url').val();

var image_delete_url = $('#image_delete_url').val();
var remove_question_url = $('#remove_question_url').val();

var render_question_url = $('#render_question_url').val();
var item_name_check_url = $('#item_name_check_url').val();
var image_order_url = $('#image_order_url').val();


/* Begin Tabs NEXT & PREV buttons */
	$('.btnNext').click(function(){
	  $('.nav-tabs > .active').next('li').find('a').trigger('click');
	});

	 $('.btnPrevious').click(function(){
	  $('.nav-tabs > .active').prev('li').find('a').trigger('click');
	});
	/* End Tabs NEXT & PREV buttons */

 	/* Begin when loading page first tab opened */
 	$(function(){
 		$('.nav-tabs li:first').addClass("active");
 		$('.tab-content div:first').addClass('active');
 	});

	var csrfToken = $('meta[name="csrf-token"]').attr('content');

	$('#option').hide();

    $('.vendoritemquestion-question_answer_type').on('change',function (){

		var type = $(this).val();

		if(type =='selection')
		{
			$(this).next('.price_val').remove();
			var j = $(this).attr('id').replace(/vendoritemquestion-question_answer_type/, '');
			$('#option').show();

			$(this).after('<div class="selection"><input type="text" class="form-control" name="Vendoritemquestion['+j+'][text][0][]" placeholder="Question" id="question" style="width:50%;float:left;"><input type="text" class="form-control" name="Vendoritemquestion['+j+'][price][0][]" placeholder="Price (Optional)" id="price" style="width:40%;float:left;"><input type="button" class="add_question" id=\add_question'+j+'" data-option-count="1" name="Addss" value="Add Selection"></div>');
		
		} else if(type =='image' ||  type =='text') {

			$(this).next('.selection').remove();
			$(this).next('.price_val').remove();
			var j = $(this).attr('id').replace(/vendoritemquestion-question_answer_type/, '');
			$('#option').show();

			$(this).after('<div class="price_val"><input type="text" class="form-control" name="Vendoritemquestion['+j+'][price][]" placeholder="Price (Optional)" id="price" style="width:40%;float:left;"></div>');
		}

		// Add selection for questions //
	});

	var p = 1;

	$('.add_question').on('click',function(){
		var j = $(this).attr('id').replace(/add_question/, '');
		var p = $(this).attr('data-option-count');
		$(this).before('<div class="selection"><input type="text" class="form-control" name="Vendoritemquestion['+j+'][text]['+p+'][]" placeholder="Question" id="question" style="width:50%;float:left;"><input type="text" class="form-control" name="Vendoritemquestion['+j+'][price]['+p+'][]" placeholder="Price (Optional)" id="price" style="width:45%;float:left;"></div>');p++;
		$(this).attr('data-option-count',p);
	});

    $('#vendoritem-category_id').change(function (){
        
        var id = $('#vendoritem-category_id').val();
        var path = load_sub_category_url;
        
        $('.loadingmessage').show();
        
        $.ajax({
	        type: 'POST',
	        url: path,
	        data: { id: id ,_csrf : csrfToken},
	        success: function( data ) {
	        	$('.loadingmessage').hide();
	             $('#vendoritem-subcategory_id').html(data);
	        }
        });
    });

	//* Load Child Category *//
    $('#vendoritem-subcategory_id').change(function (){

		var id = $('#vendoritem-subcategory_id').val();
        var path = load_child_category_url;
        
        $('.loadingmessage').show();
        
        $.ajax({
	        type: 'POST',
	        url: path,
	        data: { id: id ,_csrf : csrfToken},
	        success: function( data ) {
				$('.loadingmessage').hide();
	            $('#vendoritem-child_category').html(data);
	        }
        });
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

$(function(){
	CKEDITOR.replace('vendoritem-item_description');
	CKEDITOR.replace('vendoritem-item_additional_info');
	CKEDITOR.replace('vendoritem-item_price_description');
	CKEDITOR.replace('vendoritem-item_customization_description');

	CKEDITOR.replace('vendoritem-item_description_ar');
	CKEDITOR.replace('vendoritem-item_additional_info_ar');
	CKEDITOR.replace('vendoritem-item_price_description_ar');
	CKEDITOR.replace('vendoritem-item_customization_description_ar');
});

// Question and answer begin
/* 	BEGIN Themes and groups multiselect widget */
$(function(){
 	$('#vendoritem-themes').multiselect({
		'enableFiltering': true,
        'filterPlaceholder': 'Search for something...'
    });

  	$('#vendoritem-groups').multiselect({
		'enableFiltering': true,
        'filterPlaceholder': 'Search for something...'
    });
});

/* Price chart for item */
var j= 2;

function addPrice(tis)
{
	$(tis).before('<div class="controls'+j+'"><input type="text" id="vendoritem-item_from" class="form-control from_range_'+j+'" name="vendoritem-item_price[from][]" multiple="multiple" Placeholder="From range"><input type="text" id="vendoritem-item_to" class="form-control to_range_'+j+'" name="vendoritem-item_price[to][]" multiple = "multiple" Placeholder="To range"><input type="text" id="item_price_per_unit" class="form-control price_kd'+j+'" name="vendoritem-item_price[price][]" multiple = "multiple" Placeholder="Price">KD<input type="button" name="remove" id="remove" value="Remove" class="remove_price" onClick="removePrice(this)" /></div>');
	j++;
}

function removePrice(tis)
{
	var r = confirm("Are you sure want to delete?");
	
	if (r == true) {
		$(this).parent().remove();
        return false;
	}
}

/* Price chart for item */

/* END Themes and groups multiselect widget */
/* BEGIN bootstrap file input widget for image preview */
$(document).on('ready', function() {
	
	$('.file-block').hide();

	/* Sort item image */
	if(!imagedata) { 

		$('#vendoritem-image_path').fileinput({
		    showUpload:false,
			showRemove:false,
			overwriteInitial: false,
		    uploadUrl : '/dummy/dummy',
		});
	} else {

        /*var text = '{ "employees" : [' +
            '{ "firstName":"John" , "lastName":"Doe" },' +
            '{ "firstName":"Anna" , "lastName":"Smith" },' +
            '{ "firstName":"Peter" , "lastName":"Jones" } ]}';

        var obj12 = JSON.parse(text);

        console.log(obj12);
*/
        //var reviewtext = '{"reviewer1": "Pam", "stars1": 2, "text1": "Pretty good, but could have used more Jason"}'; +
        //var moviereviewtext = '{"title": "Friday the 13th", "year": 1980, "reviews": [{"reviewer": "Pam", "stars": 3, "text": "Pretty good, but could have used more Jason"}, {"reviewer": "Alice", "stars": 4, "text": "The end was good, but a little unsettling"}]}';
        //var jsonobj = eval("(" + moviereviewtext + ")");
        //var jsonobj = eval("(" + action + ")");

        console.log(action);

        var temp = img.split(',');
        var pluginArrayArg = new Array();

		for (a in temp ) {
			var html = $.parseHTML( temp[a] );

            var jsonArg = new Object();
            jsonArg.url = '/vendoritem/deleteitemimage';
            jsonArg.key = $(html).data('key');
            pluginArrayArg.push(jsonArg);
		}

        ////var pluginArrayArg = new Array();
        ////pluginArrayArg.push(jsonArg1);
        ////pluginArrayArg.push(jsonArg2);
        //
        //
        //var obj1 = JSON.stringify(pluginArrayArg);
		//var ob2 = jQuery.parseJSON(obj1)
		////console.log(ob2[1]);
        //var $var1 = '{url: "/vendoritem/deleteitemimage",key: 745},';
        //var $var2 = '{url: "/vendoritem/deleteitemimage",key: 741}';
		//var res = $var1.concat($var2);
        //console.log({url: "/vendoritem/deleteitemimage",key: 745);
		//console.log({url: "/vendoritem/deleteitemimage",key: 745},{url: "/vendoritem/deleteitemimage",key: 734});
		//var p = JSON.parse(action);
		//console.log(p);

		$('#vendoritem-image_path').fileinput({
	    	showUpload:false,
			showRemove:false,
			initialPreview: img.split(','),
			initialPreviewConfig: action,
			overwriteInitial: false,
	    	uploadUrl : '/dummy/dummy',
		});
	}

	/* Sort guide image */
	if(guideimagedata) { 
		$('#vendoritem-guide_image').fileinput({
	    	showUpload:false,
			showRemove:false,

			initialPreview: [
				img1,
			],
			initialPreviewConfig: [
			   	action1,
			],
			overwriteInitial: false,
	    	uploadUrl : '/dummy/dummy',
	   	});
	}else{
		$('#vendoritem-guide_image').fileinput({
	    	showUpload:false,
			showRemove:false,
			overwriteInitial: false,
	    	uploadUrl : '/dummy/dummy',
	   	});
	}
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

if(isNewRecord) {
    $('#vendoritem-item_for_sale').prop('checked', true);
} else {
	if(item_for_sale=='Yes') {
		 $('#vendoritem-item_for_sale').prop('checked', true);
	} else { 
		$('#vendoritem-item_for_sale').prop('checked', false);
	}
	
	if(item_status=='Active') {
		$('#vendoritem-item_status').prop('checked', true);
	} else { 
		$('#vendoritem-item_status').prop('checked', false);
	}
} 
/* END bootstrap file input widget for image preview */

/*BEGIN  VALIDATION */

$('#validone1').click(function() {

	if($('#test').val()==1)
	{
  		return false;
	}

  	if($('#vendoritem-item_name').val()=='')
	{
		$('.field-vendoritem-item_name').addClass('has-error');
		$('.field-vendoritem-item_name').find('.help-block').html('Item name cannot be blank.');
		return false;
  	}

  	if($('#vendoritem-category_id').val()=='')
	{
		$('.field-vendoritem-category_id').addClass('has-error');
		$('.field-vendoritem-category_id').find('.help-block').html('Category cannot be blank.');
		return false;
  	}

  	if($('#vendoritem-subcategory_id').val()=='')
	{
		$('.field-vendoritem-subcategory_id').addClass('has-error');
		$('.field-vendoritem-subcategory_id').find('.help-block').html('Subcategory cannot be blank.');
		return false;
  	}

  	if($('#vendoritem-child_category').val()=='')
	{
		$('.field-vendoritem-child_category').addClass('has-error');
		$('.field-vendoritem-child_category').find('.help-block').html('Child category cannot be blank.');
		return false;
  	}

	/*   //validate email already exist or not
 	var item_len = $('#vendoritem-item_name').val().length;

  	if($('#vendoritem-item_name').val()=='')
	{
	 	$('.field-vendoritem-item_name').addClass('has-error');
			$('.field-vendoritem-item_name').find('.help-block').html('Item name cannot be blank.');
			return false;
	 	} else if(item_len < 4){
			$('.field-vendoritem-item_name').addClass('has-error');
 			$('.field-vendoritem-item_name').find('.help-block').html('Item name minimum 4 letters.');
			return false;
	 } else if(item_len > 3) {
  		var mail = $('#vendoritem-item_name').val();
        var path = '".Url::to(['/vendoritem/itemnamecheck1'])."';
        
        $('.loadingmessage').show();
        
        $.ajax({
	        type: 'POST',
	        url: path, //url to be called
	        data: { item: mail , item_id : item_id, _csrf : csrfToken}, //data to be send
	        success: function( data ) {
				$('#test').val(mail);
	            
	            if(data > 0) {
		            $('.loadingmessage').hide();
					$('.field-vendoritem-item_name').removeClass('has-success');
					$('.field-vendoritem-item_name').addClass('has-error');
					$('.field-vendoritem-item_name').find('.help-block').html('Item name already exists.');
					$('.field-vendoritem-item_name').focus();
					$('#test').val(1);
		   			return false;
				} else {
					$('.field-vendoritem-item_name').find('.help-block').html('');
					$('.loadingmessage').hide();
					$('#test').val(0);
				}
	         }
        });
  	} else {
	  	return true;
	}*/
});

/* BEGIN TAB 2 */
$('#validtwo2').click(function() {

  	if($('#vendoritem-item_name').val()=='')
	{
		$('.field-vendoritem-item_name').addClass('has-error');
		$('.field-vendoritem-item_name').find('.help-block').html('Item name cannot be blank.');
		return false;
  	}

    if($('#vendoritem-category_id').val()=='')
	{
		$('.field-vendoritem-category_id').addClass('has-error');
		$('.field-vendoritem-category_id').find('.help-block').html('Category cannot be blank.');
		return false;
  	}

    if($('#vendoritem-subcategory_id').val()=='')
	{
		$('.field-vendoritem-subcategory_id').addClass('has-error');
		$('.field-vendoritem-subcategory_id').find('.help-block').html('Subcategory cannot be blank.');
		return false;
    }

  	if($('#vendoritem-child_category').val()=='')
	{
		$('.field-vendoritem-child_category').addClass('has-error');
		$('.field-vendoritem-child_category').find('.help-block').html('Child category cannot be blank.');
		return false;
    }

    if($('#vendoritem-type_id').val()=='')
	{
		$('.field-vendoritem-type_id').addClass('has-error');
		$('.field-vendoritem-type_id').find('.help-block').html('Item type cannot be blank.');
		return false;
  	}

  	var messageLength = CKEDITOR.instances['vendoritem-item_description'].getData().replace(/<[^>]*>/gi, '').length;
       
    if(!messageLength ) {
        $('.field-vendoritem-item_description').addClass('has-error');
	    $('.field-vendoritem-item_description').find('.help-block').html('Item description cannot be blank.');
		return false;
    } else {
   		$('.field-vendoritem-item_description').removeClass('has-error');
  		$('.field-vendoritem-item_description').find('.help-block').html('');
   		return true;
    }
});

/* BEGIN TAB 3 */
$('#validthree3').click(function() {

    if($('#vendoritem-item_name').val()=='')
	{
		$('.field-vendoritem-item_name').addClass('has-error');
		$('.field-vendoritem-item_name').find('.help-block').html('Item name cannot be blank.');
		return false;
    }

    if($('#vendoritem-category_id').val()=='')
	{
		$('.field-vendoritem-category_id').addClass('has-error');
		$('.field-vendoritem-category_id').find('.help-block').html('Category cannot be blank.');
		return false;
  	}

    if($('#vendoritem-subcategory_id').val()=='')
	{
		$('.field-vendoritem-subcategory_id').addClass('has-error');
		$('.field-vendoritem-subcategory_id').find('.help-block').html('Subcategory cannot be blank.');
		return false;
    }

  	if($('#vendoritem-child_category').val()=='')
	{
		$('.field-vendoritem-child_category').addClass('has-error');
		$('.field-vendoritem-child_category').find('.help-block').html('Child category cannot be blank.');
		return false;
    }

	/* BEGIN Validate item for sale yes or no */
    if($('#vendoritem-item_for_sale').prop('checked') == true)
    {
		if($('#vendoritem-item_amount_in_stock').val()=='')
		{
			$('.field-vendoritem-item_amount_in_stock').addClass('has-error');
			$('.field-vendoritem-item_amount_in_stock').find('.help-block').html('Item number of stock cannot be blank.');
			return false;
 		}

    	if($('#vendoritem-item_default_capacity').val()=='')
		{
			$('.field-vendoritem-item_default_capacity').addClass('has-error');
			$('.field-vendoritem-item_default_capacity').find('.help-block').html('Item default capacity cannot be blank.');
			return false;
  		}

 	 	if($('#vendoritem-item_how_long_to_make').val()=='')
		{
			$('.field-vendoritem-item_how_long_to_make').addClass('has-error');
			$('.field-vendoritem-item_how_long_to_make').find('.help-block').html('No of days delivery cannot be blank.');
			return false;
 	 	}

 	 	if($('#vendoritem-item_minimum_quantity_to_order').val()=='')
		{
			$('.field-vendoritem-item_minimum_quantity_to_order').addClass('has-error');
			$('.field-vendoritem-item_minimum_quantity_to_order').find('.help-block').html('Item minimum quantity to order cannot be blank.');
			return false;
  		}
   	}

  	if($('#vendoritem-type_id').val()=='')
	{
		$('.field-vendoritem-type_id').addClass('has-error');
		$('.field-vendoritem-type_id').find('.help-block').html('Item type cannot be blank.');
		return false;
    } else {
    	return true;
    }
});
/* END TAB 3 */

$('.complete').click(function()
{
	if($('.file-preview-thumbnails img').length <= 0)
	{
		$('.field-vendoritem-image_path').addClass('has-error');
		$('.field-vendoritem-image_path').find('.help-block').html('Upload atleast one image.');
		return false;
	}
});

/* Guide images and descrition show / hide */

$(function(){
	$('.custom_description').hide();
	$('.custom_description_ar').hide();
	$('.guide_image').hide();
	$('.mandatory').show();

	$('#vendoritem-item_for_sale').click(function()
	{
		if($(this).is(':checked'))
		{
			$('.custom_description').hide();
			$('.custom_description_ar').hide();
			$('.guide_image').hide();
			$('.mandatory').show();
		} else {
			$('.mandatory').hide();
			$('.custom_description').show();
			$('.custom_description_ar').show();
			$('.guide_image').show();
		}
	});

	if(isNewRecord) {
		if($('#vendoritem-item_for_sale').prop('checked') == true){
			$('.custom_description').hide();
			$('.custom_description_ar').hide();
			$('.guide_image').hide();
			$('.mandatory').show();
		}
		else
		{
			$('.custom_description').show();
			$('.custom_description_ar').show();
			$('.guide_image').show();
			$('.mandatory').hide();
		}
	} 
});

/* Guide images and descrition show / hide */

/* END VALIDATION */

/* BEGIN Vendor item check exist or not */
$(function () {

 	//$('#vendoritem-item_name').on('keyup keypress focusout',function () {
  	$('#w0').on('afterValidateAttribute', function (event, messages) {

		if($('#vendoritem-item_name').val().length > 3) {

			var mail = $('#vendoritem-item_name').val();
	        var path = item_name_check_url;
	        //$('.loadingmessage').show();
	        
	        $.ajax({
		        type: 'POST',
		        url: path,
		        data: { item: mail ,item_id : item_id, _csrf : csrfToken},
		        async:false,
		        success: function( data ) {
					
					$('#test').val(mail);

		            if(data > 0) {
						/*$('.loadingmessage').hide();
						$('.field-vendoritem-item_name').removeClass('has-success');
						$('.field-vendoritem-item_name').addClass('has-error');*/
						$('.field-vendoritem-item_name').find('.help-block').html('Item name already exists.');
						//	$('.field-vendoritem-item_name').focus();
						$('#test').val(1);
			   			//return false;
					} else {
			    		//alert(234);
						$('.field-vendoritem-item_name').find('.help-block').html('');
						//$('.loadingmessage').hide();
						$('#test').val(0);
					}
		        }
	        });
	  	}
	});
});
/* END Vendor item check exist or not */

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
