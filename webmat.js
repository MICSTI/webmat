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
	
	// change buttons according to step position	
	switch (_tabno) {
		case 1:
			jQuery("#the-tool-button-back").hide();
			jQuery("#the-tool-button-next").show();
			jQuery("#the-tool-button-submit").hide();
			
			break;
			
		case 4:
			jQuery("#the-tool-button-back").show();
			jQuery("#the-tool-button-next").hide();
			jQuery("#the-tool-button-submit").show();
			
			break;
			
		default:
			jQuery("#the-tool-button-back").show();
			jQuery("#the-tool-button-next").show();
			jQuery("#the-tool-button-submit").hide();
		
			break;
	}
}

/**
	Displays the tool, after the button on the welcome page has been clicked.
*/
var showTheTool = function() {
	jQuery(".the-tool-intro").hide();
	jQuery(".the-tool-wrapper").show();
}

/**
	Creates a form dynamically, adds all the checked items from the tool page and submits it then automatically.
*/
var submitToolForm = function() {
	var form = jQuery('<form>', {
					id: 'the-tool-dynamic-form',
					method: 'post',
					action: '../results',
					style: 'display: none;'
				}).appendTo('body');
					
	jQuery(".category-choice-selected").each(function(idx, item) {
		var elem = jQuery(item).attr("data-id");
		
		var box = jQuery('<input>', { type: 'checkbox', name: elem, checked: 'checked'  });
		
		form.append(box);
	});		
				
	form.submit();
}

jQuery(document).ready(function() {
	// add page background color
	var nav = jQuery("#site-navigation");
	
	var nav_height = nav.offset().top + (nav.outerHeight() / 2);
	
	jQuery('<div>', {
		style: 'position: absolute; left: 0; top: 0; width: 100%; height: ' + nav_height + 'px; background-color: #ffb81c; z-index: -1;'
	}).insertBefore('#page');
	
	// check if we should switch to a specific question
	var hash = window.location.hash;
	
	if (hash.startsWith("#question-")) {
		var tabNo = parseInt(hash.substr("#question-".length));
		
		showTheTool();
	} else {
		var tabNo = 1;
	}
	
	// circle navigation
	jQuery(".the-tool-circle").on("click", function() {
		// select tab
		var category = jQuery(this).attr("data-category");
		tabNo = jQuery(this).attr("data-no");
		
		selectCategory(category, parseInt(tabNo));
	});
	
	// buttons
	jQuery("#the-tool-button-back").on("click", function() {
		if (CATEGORIES.hasOwnProperty(parseInt(tabNo) - 1)) {
			tabNo--;
			selectCategory(CATEGORIES[tabNo], tabNo);
		}
	});
	
	jQuery("#the-tool-button-next").on("click", function() {
		if (CATEGORIES.hasOwnProperty(parseInt(tabNo) + 1)) {
			tabNo++;
			selectCategory(CATEGORIES[tabNo], tabNo);
		}
	});
	
	jQuery("#the-tool-button-submit").on("click", submitToolForm);
	
	// category choices
	jQuery(".category-choice").on("click", function() {
		jQuery(this).toggleClass("category-choice-selected");
	});
	
	// add no-touch class for hover problem on touch devices
	if (!("ontouchstart" in document.documentElement)) {
		document.documentElement.className += " no-touch";
	}
});