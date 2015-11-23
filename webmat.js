var CATEGORIES = {
	1: "age",
	2: "field",
	3: "workplace",
	4: "size"
}

var selectCategory = function(_id) {
	// remove active classes
	jQuery(".the-tool-content").removeClass("the-tool-content-active");
	jQuery(".the-tool-circle").removeClass("the-tool-category-selected");
	
	// add active classes
	jQuery("#content-" + _id).addClass("the-tool-content-active");
	jQuery("#category-" + _id).addClass("the-tool-category-selected");
}

jQuery(document).ready(function() {
	var tabNo = 1;
	
	// circle navigation
	jQuery(".the-tool-circle").on("click", function() {
		// select tab
		var category = jQuery(this).attr("data-category");
		tabNo = jQuery(this).attr("data-no");
		
		selectCategory(category);
	});
	
	// buttons
	jQuery("#the-tool-button-next").on("click", function() {
		selectCategory(CATEGORIES[++tabNo]);
	});
});