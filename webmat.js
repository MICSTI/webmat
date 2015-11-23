var CATEGORIES = {
	1: "age",
	2: "field",
	3: "workplace",
	4: "size"
}

var selectCategory = function(_id, _tabno) {
	// remove active classes
	jQuery(".the-tool-content").removeClass("the-tool-content-active");
	jQuery(".the-tool-circle").removeClass("the-tool-category-selected");
	
	// add active classes
	jQuery("#content-" + _id).addClass("the-tool-content-active");
	jQuery("#category-" + _id).addClass("the-tool-category-selected");
	
	// change buttons
	if (_tabno >= 4) {
		jQuery("#the-tool-button-next").hide();
		jQuery("#the-tool-button-submit").show();
	} else {
		jQuery("#the-tool-button-next").show();
		jQuery("#the-tool-button-submit").hide();
	}
}

jQuery(document).ready(function() {
	var tabNo = 1;
	
	// circle navigation
	jQuery(".the-tool-circle").on("click", function() {
		// select tab
		var category = jQuery(this).attr("data-category");
		tabNo = jQuery(this).attr("data-no");
		
		selectCategory(category, tabNo);
	});
	
	// buttons
	jQuery("#the-tool-button-back").on("click", function() {
		if (CATEGORIES.hasOwnProperty(tabNo - 1)) {
			tabNo--;
			selectCategory(CATEGORIES[tabNo], tabNo);
		}
	});
	
	jQuery("#the-tool-button-next").on("click", function() {
		if (CATEGORIES.hasOwnProperty(tabNo + 1)) {
			tabNo++;
			selectCategory(CATEGORIES[tabNo], tabNo);
		}
	});
	
	// category choices
	jQuery(".category-choice").on("click", function() {
		jQuery(this).toggleClass("category-choice-selected");
	});
});