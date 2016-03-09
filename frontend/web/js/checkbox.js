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

	// register clicks and toggle classes
	labelEl.addEventListener("click",function(){
		if (classie.has(searchEl,"focus")) {
			
			classie.remove(searchEl,"focus");
			classie.remove(labelEl,"active");
		} else {
			
			classie.add(searchEl,"focus");
			classie.add(labelEl,"active");
		}
	});
	
	labelE2.addEventListener("click",function(){
		if (classie.has(searchE2,"focus")) {
			
			classie.remove(searchE2,"focus");
			classie.remove(labelE2,"active");
		} else {
			
			classie.add(searchE2,"focus");
			classie.add(labelE2,"active");
		}
	});

	// register clicks outisde search box, and toggle correct classes
	document.addEventListener("click",function(e){
		var clickedID = e.target.id;		
		if (clickedID != "search-terms" && clickedID != "search-labl" && clickedID != "search-label") {			
			if (classie.has(searchEl,"focus")) {
				classie.remove(searchEl,"focus");
				classie.remove(labelEl,"active");
			}
		}
	}); 
}(window));