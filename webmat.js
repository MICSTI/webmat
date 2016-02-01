var CATEGORIES = {
	1: "age",
	2: "field",
	3: "workplace",
	4: "size"
};

var API_URL = "/index.php?__api=";

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
	Clears all canvases on the screen
*/
var clearAllCanvases = function() {
	jQuery("canvas").each(function(idx, item) {
		item.width = item.width;
	});
}

/**
	Clears all progress bars on the screen
*/
var clearAllProgressBars = function() {
	// hide progress bar values
	jQuery(".stats-info-key-progress-value").css("opacity", 0);
	
	// reset widths
	jQuery(".stats-info-key-progress-filled").css("width", "");
	
	// remove hover function
	jQuery(".stats-info-key-progress-value").off("hover");
}

/**
	Animates the progress bars
*/
var animateProgressBars = function() {
	var bars = jQuery(".stats-info-key-progress-filled");
	
	bars.each(function(idx, elem) {
		var callback = idx == (bars.length - 1) ? function() {
			var values = jQuery(".stats-info-key-progress-value");
			
			values.each(function(idx2, elem2) {
				var anotherCallback = idx2 == (values.length - 1) ? function() {
					addProgressBarHover();
				} : function() {};
				
				jQuery(elem2).animate( {opacity: 1}, {
					duration: 250,
					complete: anotherCallback
				} );
			});
		} : function() {};
		
		jQuery(elem).animate({
			width: jQuery(elem).attr("data-value") + "%",
		}, {
			duration: 750,
			complete: callback
		});
	});
}

/**
	Inits the paging on a page
*/
var initPaging = function() {
	if (jQuery(".paging").length > 0) {
		jQuery(".paging-direction-indicator").on("click", function() {
			var direction = jQuery(this).attr("data-direction");
			
			if (direction == "back") {
				// back
				var _page = parseInt(jQuery("#paging-current").text()) - 1;
				
				if (_page == 1) {
					jQuery(this).css("visibility", "hidden");
				}
				
				jQuery(".paging-direction-indicator[data-direction=forward]").css("visibility", "visible");
			} else {
				// forward
				var _page = parseInt(jQuery("#paging-current").text()) + 1;
				
				if (_page == parseInt(jQuery("#paging-max").text())) {
					jQuery(this).css("visibility", "hidden");
				}
				
				jQuery(".paging-direction-indicator[data-direction=back]").css("visibility", "visible");
			}
			
			jQuery("#paging-current").text(_page);
			
			// make AJAX request
			jQuery.get(API_URL + "result_details&page=" + _page, function(response) {
				// check response status
				if (response.status !== undefined && response.status === "ok") {
					jQuery("#request-details-content").fadeOut(250, function() {
						jQuery(this).html(response.data).fadeIn(250, function() {
							// init request detail loading
							initRequestDetailLoading();
							
							// load country indicators
							loadCountryIndicators();
						});
					});
				} else {
					console.log("AJAX ERROR", response.message);
				}
			});
		});
	}
};

var initRequestDetailLoading = function() {
	jQuery(".stats-info-group").off("click");
	jQuery(".stats-info-group").on("click", function() {
		var details = jQuery(this).children(".request-detail-wrapper").first();
		
		var _id = jQuery(this).attr("data-id");
		
		if (details.html() != "") {
			details.fadeOut(120, function() {
				details.html("");
			});
		} else {
			var detailHtml = "";
			
			detailHtml += getInfoFromIp(jQuery(this).children(".float-right").children(".country-indicator").first());
			
			// show
			details.html(detailHtml)
				   .fadeIn(250, function() {
					   // add loading indicator
					   var loading = jQuery("<div>", {
						   class: "details-loading",
						   text: "Fetching details..."
					   });
					   
					   jQuery(this).append(loading);
					   
					   var __elem = this;
					   
					   // load request details
					   jQuery.get(API_URL + "request_details&id=" + _id, function(response) {
							// check response status
							if (response.status !== undefined && response.status === "ok") {
								var _data = response.data;
								
								var _request = _data["request"];
								
								var html = "<div><div class='request-details'>";
								
								html += "<div class='request-details-title'>Request details</div>";
								
								Object.getOwnPropertyNames(_request).forEach(function(category) {
									var elements = _request[category] || [];
									
									html += "<div class='request-details-category'>";
										html += "<div class='request-details-category-title'>" + category + "</div>";
										html += "<div class='request-details-category-elements'>";
											elements.forEach(function(prop) {
												var text = prop["display"] || "";
												html += "<span>" + text + "</span>";
											});
										html += "</div>";
									html += "</div>";
								});
								
								html += "</div></div>";
								
								// survey
								var _survey = _data["survey"] || [];
								
								if (_survey.length > 0) {
									html += "<div>SURVEY</div>";
								}
								
								// remove loading indicator
								loading.remove();
								
								// append detail HTML
								jQuery(__elem).append(html);
							} else {
								console.log("AJAX ERROR", response.message);
								
								// remove loading indicator
								loading.remove();
							}
						});
				   });
		}
	});
};

var getInfoFromIp = function(elem) {
	var html = "";
	
	html += "<div class='ip-info-detail'>";
		if (elem !== undefined) {
			html += "<div class='ip-info-title'>IP Address Info (approximately)</div>";
							
			var attrs = [{
				attr: "data-country",
				display: "Country"
			}, {
				attr: "data-country-code",
				display: "Country code"
			}, {
				attr: "data-region-name",
				display: "Region name"
			}, {
				attr: "data-city",
				display: "City"
			}, {
				attr: "data-org",
				display: "Organisation"
			}, {
				attr: "data-mobile",
				display: "On mobile device"
			}];
			
			attrs.forEach(function(item, idx) {
				var value = elem.attr(item.attr);
				
				if (value == "true") {
					value = "Yes";
				} else if (value == "false") {
					value = "No";
				}
				
				html += "<div class='ip-info-attr'>";
					html += "<span class='bold'>" + item.display + "</span>: ";
					html += "<span>" + value + "</span>";
				html += "</div>";
			});
		} else {
			html += "<div>Unfortunately, no info available. Sorry!</div>";
		}
	html += "</div>";
	
	return html;
};

/**
	Loads all country indicators and fades them in upon fetch completion
*/
var loadCountryIndicators = function() {
	// build JSON array
	var query = [];
	var fields = "country,countryCode,regionName,city,lat,lon,isp,org,mobile,query";
	
	jQuery(".country-indicator").each(function(idx, item) {
		var ip = jQuery(item).attr("data-ip");
		
		if (ip !== undefined) {
			query.push({
				query: ip,
				fields: fields
			});
		}
	});
	
	jQuery.ajax({
		url: "http://ip-api.com/batch",
		data: JSON.stringify(query),
		method: "POST"
	}).done(function(data) {
		// parse response array
		data.forEach(function(item) {
			jQuery(".country-indicator[data-ip='" + item.query + "']")
						.text(item.country)
						.attr({
							"data-country": item.country,
							"data-country-code": item.countryCode,
							"data-region-name": item.regionName,
							"data-city": item.city,
							"data-lat": item.lat,
							"data-lon": item.lon,
							"data-isp": item.isp,
							"data-org": item.org,
							"data-mobile": item.mobile
						});
		});
		
		// fade country indicators in
		jQuery(".country-indicator").fadeIn(250);
	}).fail(function(err) {
		console.log("Error fetching IP info", err);
	});
};

/**
	Hides all country indicators
*/
var hideCountryIndicators = function() {
	jQuery(".country-indicator").hide();
};

/**
	Adds a hover function to all progress bars
*/
var addProgressBarHover = function() {
	jQuery(".stats-info-key-progress-value").hover(function() {
		jQuery(this).html(jQuery(this).attr("data-real"));
	}, function() {
		jQuery(this).html(jQuery(this).attr("data-value") + "%");
	});
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
	
	// stats
	var loadCharts = function(tab) {
		switch (tab) {
			case "general":
				// OS
				var osCtx = document.getElementById("chart-os").getContext("2d");
				window.osChart = new Chart(osCtx).Doughnut(osData, { responsive: true });
				
				var browserCtx = document.getElementById("chart-browser").getContext("2d");
				window.browserChart = new Chart(browserCtx).Doughnut(browserData, { responsive: true });
				
				break;
				
			case "survey":
				var charts = ["helpful", "funded", "use"];
				
				charts.forEach(function(item, idx) {
					var data = window["stats_" + item];
					
					if (data !== undefined) {
						new Chart(document.getElementById("chart-" + item).getContext("2d")).Doughnut(data, { responsive: true });
					}
				});
			
				break;
				
			default:
				break;
		}
	}
	
	var statsContent = jQuery(".stats-content");
	
	if (statsContent.length > 0) {
		// init nav elements
		jQuery(".stats-nav-elem").on("click", function() {
			// check if it is not already active
			if (!jQuery(this).hasClass("stats-nav-elem-active")) {
				// clear active class from other elements
				jQuery(".stats-nav-elem").removeClass("stats-nav-elem-active");
				
				// add it to this one
				jQuery(this).addClass("stats-nav-elem-active");
				
				// get tab
				var tab = jQuery(this).attr("data-tab");
				
				// fade out active tab
				jQuery(".stats-tab-content-active").fadeOut(200, function() {
					// remove active class
					jQuery(this).removeClass("stats-tab-content-active");
					
					// clear canvases
					clearAllCanvases();
					
					// clear progress bars
					clearAllProgressBars();
					
					// hide country indicators
					hideCountryIndicators();
					
					// fade new tab in
					jQuery("#stats-content-" + tab).fadeIn(300, function() {
						// add active class
						jQuery(this).addClass("stats-tab-content-active");
						
						// load charts
						loadCharts(tab);
						
						// animate progress bars
						animateProgressBars();
						
						// load country indicators
						loadCountryIndicators();
						
						// add paging
						initPaging();
						
						// init request detail loading
						initRequestDetailLoading();
					});
				});
			}
		});
		
		// auto-load default tab
		var defaultTab = jQuery("a[data-default=true]").attr("data-tab");
		loadCharts(defaultTab);
	}
	
	// add no-touch class for hover problem on touch devices
	if (!("ontouchstart" in document.documentElement)) {
		document.documentElement.className += " no-touch";
	}
});