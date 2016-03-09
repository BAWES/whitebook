/* ------------------------------------------------------------ *\
|* ------------------------------------------------------------ *|
|* Some JS to help with our search
|* ------------------------------------------------------------ *|
\* ------------------------------------------------------------ */
(function(window){

	// get vars
	var searchEl = document.querySelector("#input1");
	var labelEl = document.querySelector("#label1");
	var searchE2 = document.querySelector("#input");
	var labelE2 = document.querySelector("#label");
	//event-detail search
	var searchE3 = document.querySelector("#input2");
	var labelE3 = document.querySelector("#label2");

	//event-detail name
	var searchE4 = document.querySelector("#input3");
	var labelE4 = document.querySelector("#label3");
	var searchbox1 = document.querySelector("#search_input_header");

	// register clicks and toggle classes
	if(labelEl){
	labelEl.addEventListener("click",function(){
		if (classie.has(searchEl,"focus")) {
			
			classie.remove(searchEl,"focus");
			classie.remove(labelEl,"active");
		} else {
			
			classie.add(searchEl,"focus");
			classie.add(labelEl,"active");
		}

	});
}
if(labelE2){
	labelE2.addEventListener("click",function(){
		if (classie.has(searchE2,"focus")) {
			
			classie.remove(searchE2,"focus");
			classie.remove(labelE2,"active");
		} else {
			
			classie.add(searchE2,"focus");
			classie.add(labelE2,"active");
		}
	});
}

	//event-detail search
if(labelE3){
	labelE3.addEventListener("click",function(){
		if (classie.has(searchE3,"focus")) {
			
			classie.remove(searchE3,"focus");
			classie.remove(labelE3,"active");
		} else {
			
			classie.add(searchE3,"focus");
			classie.add(labelE3,"active");
		}
	});
}

	//event-detail name search
if(labelE4){
	labelE4.addEventListener("click",function(){
		if (classie.has(searchE4,"focus")) {
			
			classie.remove(searchE4,"focus");
			classie.remove(labelE4,"active");
		} else {
			
			classie.add(searchE4,"focus");
			classie.add(labelE4,"active");
		}
	});
}

	// register clicks outisde search box, and toggle correct classes
	document.addEventListener("click",function(e){
		
		var clickedID = e.target.id;		
		
		if(clickedID == "search-labl"){
		jQuery( "#search_list" ).html('');
		var search1=jQuery("#sear_ip_head").val();
		
		pathArray = location.href.split( '/' );
		protocol = pathArray[0];
		host = pathArray[2];
		url1 = protocol + '//' + host;
		
		if(search1!=''){
		var search2 = search1.replace(' ', '-');
		var url=url1+"/search-result/";
		var path=url.concat(search2);
		window.location.href=path;
		}
		}
		if (clickedID != "sear_ip_head" && clickedID != "search-terms2"  && clickedID != "input1" && clickedID != "search-close" && clickedID != "search-labl" && clickedID != "search-terms1" && clickedID != "search-label" && clickedID != "mobile_search_fail" && clickedID != "search_list_fail1" && clickedID != "sear_button") {	
			jQuery( "#search_list" ).html('');
			jQuery( "#search_list_fail1" ).html('');
			if (classie.has(searchEl,"focus")) {
				classie.remove(searchEl,"focus");
				classie.remove(labelEl,"active");
				jQuery( "#search_list" ).html('');
				jQuery("#search_list_fail1").html('');
				jQuery("#sear_ip_head").val('');
				classie.add(searchbox1,"focus");
			}
		}
		if (clickedID != "sear_ip_head" && clickedID != "search-terms2"  && clickedID != "input1" && clickedID != "search-close" && clickedID != "search-terms1" && clickedID != "search-labl" && clickedID != "search-label" && clickedID != "mobile_search_fail" && clickedID != "search_list_fail1") {			
			jQuery( "#search_list" ).html('');
			jQuery( "#search_list_fail1" ).html('');
			if (classie.has(searchE2,"focus")) {
				classie.remove(searchE2,"focus");
				classie.remove(labelE2,"active");
				jQuery( "#search_list" ).html('');
				jQuery("#search_list_fail1").html('');
				jQuery("#sear_ip_head").val('');
			}
		}
		
		//event-detail search

		if (clickedID != "sear_ip_head" && clickedID != "search-terms2"  && clickedID != "input1" && clickedID != "search-close" && clickedID != "search-terms1" && clickedID != "search-labl" && clickedID != "exampleInputEmail2"  && clickedID != "mobile_search_fail" && clickedID != "search_list_fail1") {			
			
			if (classie.has(searchE3,"focus")) {
				classie.remove(searchE3,"focus");
				classie.remove(labelE3,"active");
				jQuery( "#search_list" ).html('');
				jQuery("#search_list_fail1").html('');
				jQuery("#sear_ip_head").val('');
			}
		}

		

 
 
 
            function setupLabel() {
                if (jQuery('.label_check input').length) {
                    jQuery('.label_check').each(function () {
                        jQuery(this).removeClass('c_on');
                    });
                    jQuery('.label_check input:checked').each(function () {
                        jQuery(this).parent('label').addClass('c_on');
                    });
                }
                ;
                if (jQuery('.label_radio input').length) {
                    jQuery('.label_radio').each(function () {
                        jQuery(this).removeClass('r_on');
                    });
                    jQuery('.label_radio input:checked').each(function () {
                        jQuery(this).parent('label').addClass('r_on');
                    });
                }
                ;
            }
            ;

            /*responsiveactive*/
           


            jQuery(document).ready(function () {
			
			 jQuery('.new_btn').click(function(e) {
                   jQuery('#myModal').modal('hide');
             });
			
                jQuery('.label_check, .label_radio').click(function () {
                    setupLabel();
                });
                setupLabel();
                jQuery(".custom-select").each(function () {

                });
                jQuery(".custom-select").change(function () {
                    var selectedOption = jQuery(this).find(":selected").text();
                    jQuery(this).next(".holder").text(selectedOption);
                }).trigger('change');
            });
            	}); 
}(window));

/*search onclick after animation start*/
// jQuery('#search-label1,#search-labl,#search-label,.has-js').each(function(index) {	
// jQuery(this).click( function() {
// if(!jQuery("#search-terms3").hasClass("animate-search"))
// {
// jQuery("#search-terms3").toggleClass("animate-search").delay(100).animate({"padding" : "5px" , "border":"none"}, '300');
// jQuery("#search-terms3").addClass("animate-search")
// }

// else
// {
// jQuery("#search-terms3").removeClass("animate-search")
// jQuery("#search-terms3").css({"padding": "5px"});
// }
// });
// });
/*search onclick after animation end*/


    


        
