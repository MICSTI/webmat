<?php
	// Default time zone
	date_default_timezone_set("Europe/Vienna");
	
	// The tool constants
	$KEY_DISPLAY = "display";
	$KEY_PROPERTY = "property";
	$KEY_TRANSLATION = "translation";
	$KEY_TYPE = "type";
	
	// paging size
	$PAGING_SIZE = 25;
	
	// chart default colors
	$COLORS = array(
					"rgba(46,204,113,1)",
					"rgba(248,148,6,1)",
					"rgba(30,139,195,1)",
					"rgba(211,84,0,1)",
					"rgba(0,177,106,1)",
					"rgba(246,71,71,1)",
					"rgba(233,212,96,1)",
					"rgba(108,122,137,1)",
					"rgba(200,247,197,1)",
					"rgba(142,68,173,1)",
					"rgba(38,166,91,1)",
					"rgba(78,205,196,1)",
					"rgba(51,110,123,1)",
					"rgba(134,226,213,1)",
					"rgba(101,198,187,1)",
					"rgba(210,77,87,1)",
					"rgba(218,223,225,1)"
					);
	
	$FIELDS_TOOL = array( "Age" => array( array($KEY_PROPERTY => "children", $KEY_DISPLAY => "Children"),
									 array($KEY_PROPERTY => "adolescents", $KEY_DISPLAY => "Adolescents"),
									 array($KEY_PROPERTY => "adults", $KEY_DISPLAY => "Adults"),
									 array($KEY_PROPERTY => "elderly", $KEY_DISPLAY => "Elderly") ),
					 "Type" => array( array($KEY_PROPERTY => "general", $KEY_DISPLAY => "General"),
									array($KEY_PROPERTY => "feeling", $KEY_DISPLAY => "Feeling"),
									array($KEY_PROPERTY => "life-satisfaction", $KEY_DISPLAY => "Life satisfaction"),
									array($KEY_PROPERTY => "flourishing", $KEY_DISPLAY => "Flourishing"),
									array($KEY_PROPERTY => "resilience", $KEY_DISPLAY => "Resilience"),
									array($KEY_PROPERTY => "mindfulness", $KEY_DISPLAY => "Mindfulness"),
									array($KEY_PROPERTY => "self-esteem-efficacy", $KEY_DISPLAY => "Self esteem / efficacy"),
									array($KEY_PROPERTY => "optimism", $KEY_DISPLAY => "Optimism"),
									array($KEY_PROPERTY => "meaning-purpose", $KEY_DISPLAY => "Meaning / Purpose"),
									array($KEY_PROPERTY => "engagement", $KEY_DISPLAY => "Engagement"),
									array($KEY_PROPERTY => "autonomy", $KEY_DISPLAY => "Autonomy"),
									array($KEY_PROPERTY => "commitment", $KEY_DISPLAY => "Commitment"),
									array($KEY_PROPERTY => "competence", $KEY_DISPLAY => "Competence") ),
					 "Workplace" => array( array($KEY_PROPERTY => "workplace", $KEY_DISPLAY => "Workplace") ),
					 "Items" => array( array($KEY_PROPERTY => "items-single", $KEY_DISPLAY => "Single-item"),
									array($KEY_PROPERTY => "general-indicators", $KEY_DISPLAY => "General indicators"),
									array($KEY_PROPERTY => "items-2-10", $KEY_DISPLAY => "2-10 items"),
									array($KEY_PROPERTY => "items-11-20", $KEY_DISPLAY => "11-20 items"),
									array($KEY_PROPERTY => "items-21-30", $KEY_DISPLAY => "21-30 items"),
									array($KEY_PROPERTY => "items-30-+", $KEY_DISPLAY => "30+ items") )
					);
					
	$FIELDS_SURVEY = array( "Questions" => array( array($KEY_PROPERTY => "helpful", $KEY_DISPLAY => "Have the suggestions been helpful for choosing a measurement?", $KEY_TYPE => "doughnut", $KEY_TRANSLATION => array("yes" => "Yes", "no" => "No")),
												array($KEY_PROPERTY => "purpose", $KEY_DISPLAY => "Does your work that you are doing have a specific title/purpose?", $KEY_TYPE => "list-plain"),
												array($KEY_PROPERTY => "occupation", $KEY_DISPLAY => "Are you a...", $KEY_TYPE => "bar-horizontal", $KEY_TRANSLATION => array(
													"student" => "Student",
													"researcher" => "Researcher",
													"professor_academic" => "Professor/Academic",
													"policy_maker" => "Policy maker",
													"health_professional" => "Health professional",
													"volunteer" => "Volunteer",
													"charity_worker" => "Charity worker",
													"other" => "Other",
													"default" => "None selected"
												)),
												array($KEY_PROPERTY => "occupation-other", $KEY_DISPLAY => "Other occupation entries", $KEY_TYPE => "list-plain"),
												array($KEY_PROPERTY => "country", $KEY_DISPLAY => "In which country are you working?", $KEY_TYPE => "bar-horizontal", $KEY_TRANSLATION => array(
													"default" => "None selected"
												)),
												array($KEY_PROPERTY => "nature", $KEY_DISPLAY => "What is the nature of work you are doing?", $KEY_TYPE => "bar-horizontal", $KEY_TRANSLATION => array(
													"clinical" => "Clinical setting",
													"industrial_organisational" => "Industrial-organisational setting",
													"health" => "Health setting",
													"school" => "School setting",
													"political" => "Political setting",
													"other" => "Other",
													"default" => "None selected"
												)),
												array($KEY_PROPERTY => "based", $KEY_DISPLAY => "Where are you currently based?", $KEY_TYPE => "list-plain"),
												array($KEY_PROPERTY => "funded", $KEY_DISPLAY => "Is your work funded?", $KEY_TYPE => "doughnut", $KEY_TRANSLATION => array("yes" => "Yes", "no" => "No")),
												array($KEY_PROPERTY => "use", $KEY_DISPLAY => "Are you going to use the recommendations from the tool?", $KEY_TYPE => "doughnut", $KEY_TRANSLATION => array("yes" => "Yes", "no" => "No")),
												array($KEY_PROPERTY => "thoughts", $KEY_DISPLAY => "Are there any thoughts you'd like to share with us?")
											)
					);

	function getSurveyQuestion($property) {
		global $FIELDS_SURVEY;
		global $KEY_PROPERTY;
		
		$questions = $FIELDS_SURVEY["Questions"];
		
		foreach ($questions as $question) {
			if ($question[$KEY_PROPERTY] == $property) {
				return $question;
			}
		}
		
		return false;
	}
					
	function getGroupPageContent($category, $host, $db_name, $db_user, $password) {
		$html = "";
		
		// set up PDO database connection
		try {
			$db = new PDO('mysql:host=' . $host . ';dbname=' . $db_name . ';charset=utf8', $db_user, $password);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			
			if (is_array($category)) {
				$where_array = array();
				
				foreach ($category as $cat) {
					array_push($where_array, "keyword_classification LIKE '%" . $cat . "%'");
				}
				
				$where = implode(" OR ", $where_array);
			} else {
				$where = "keyword_classification LIKE '%" . $category . "%'";
			}
			
			if ($where != "") {
				$where .= " AND ";
			}
			
			$where .= "active = 1";

			$query = $db->prepare("SELECT name, kw_single_item, kw_general_indicators, kw_items, abstract, keywords, example_items, original_study_details, secondary_study_details FROM measure_data WHERE " . $where);
			$query->execute();
			
			$count = 1;

			$rows = $query->rowCount();

			if ($rows > 0) {
				$results = $query->fetchAll(PDO::FETCH_ASSOC);
				
				foreach ($results as $row) {
					$id = "webmat-" . $count;
					
					$no_items = $row["kw_items"];
					
					$html .= "<div class='item'>";
						$html .= "<div class='item-title' onclick=\"toggleDisplay('" . $id . "')\">";
						
							$html .=  "<span id='" . $id . "-plus' class='detail-indicator detail-indicator-plus'>+</span>";
							$html .=  "<span id='" . $id . "-minus' class='detail-indicator detail-indicator-minus'>-</span>";
							
							$html .=  "<span>" . $row["name"] . "</span>";
						
						$html .= "</div>";
						
						$html .= "<div class='result-details' id='" . $id . "'>";
							$html .= "<div>" . $row["abstract"] . "</div>";
							$html .= "<div>" . $row["keywords"] . "</div>";
							
							$single_item = $row["kw_single_item"];
							$general_indicators = $row["kw_general_indicators"];
							
							if (!($single_item == "x" OR $general_indicators == "x")) {
								if (!empty($no_items)) {
									$html .= "<div>This scale includes " . $no_items . " items.</div>";
								}
								
								$example_items = $row["example_items"];
								
								if (!empty($example_items)) {
									$html .= "<div>Example items: " . $example_items . "</div>";
								}
							} else if ($single_item == "x") {
								$html .= "<div>This is a single-item scale.</div>";
							}
							
							$study_details = empty($row["original_study_details"]) ? $row["secondary_study_details"] : $row["original_study_details"];
							$html .= "<div>" . $study_details . "</div>";
						$html .= "</div>";
					$html .= "</div>";
					
					$count++;
				}
			} else {
				$html .= "<div class='no-results-found'>Sorry, we couldn't find any results which are suitable for your selection.</div>";
			}
		} catch(PDOException $ex) {
			$html .= "An error occurred during the database query!";
		}
		
		$html .= "<script type='text/javascript'>";
			$html .= "function toggleDisplay(_id) { ";
				$html .= "var elem = document.getElementById(_id);";
				$html .= "var plus = document.getElementById(_id + '-plus');";
				$html .= "var minus = document.getElementById(_id + '-minus');";
				
				$html .= "if (elem.style.display == 'block') {";
					$html .= "elem.style.display = 'none';";
					$html .= "plus.style.display = 'inline-block';";
					$html .= "minus.style.display = 'none';";
				$html .= "} else {";
					$html .= "elem.style.display = 'block';";
					$html .= "plus.style.display = 'none';";
					$html .= "minus.style.display = 'inline-block';";
				$html .= "}";
				
			$html .= " }";
		$html .= "</script>";
		
		return $html;
	}
	
	function addRequestMetaData($db, $ip) {
		$timestamp = date('Y-m-d H:i:s');
		
		$browser = get_browser(null, true);
		
		$os = $browser["platform"];
		$browser_name = $browser["browser"];
		$browser_version = $browser["version"];
		
		$query = $db->prepare("INSERT INTO the_tool_data (timestamp, ip, os, browser_name, browser_version) VALUES (:timestamp, :ip, :os, :browser_name, :browser_version)");
		
		$query->execute( array(':timestamp' => $timestamp, ':ip' => $ip, ':os' => $os, ':browser_name' => $browser_name, ':browser_version' => $browser_version) );
		
		return $db->lastInsertId();
	}
	
	function addRequestDetails($db, $request_id, $post, $fields, $key_property) {
		$categories = array_keys($fields);
		
		// iterate over categories
		foreach ($categories as $category) {
			$items = $fields[$category];
			
			// iterate over items in this category
			foreach ($items as $item) {
				$property = $item[$key_property];
				$value = array_key_exists($property, $post) ? 1 : 0;
				
				// build db query string
				$query = $db->prepare("INSERT INTO the_tool_details (request_id, prop, value) VALUES (:request_id, :prop, :value);");
		
				$query->execute( array(':request_id' => $request_id, ':prop' => $property, ':value' => $value) );
			}
		}
	}
	
	function addRequestMail($db, $request_id, $mail) {
		$query = $db->prepare("UPDATE the_tool_data SET mail = :mail WHERE id = :request_id");
		
		$query->execute( array(':mail' => $mail, ':request_id' => $request_id) );
	}
	
	function addSurveyDetails($db, $request_id, $post, $fields, $key_property) {
		$categories = array_keys($fields);
		
		// iterate over categories
		foreach ($categories as $category) {
			$items = $fields[$category];
				
			foreach ($items as $item) {
				$property = $item[$key_property];
				$value = isset($post[$property]) ? trim($post[$property]) : null;
				
				if ($value != null) {
					// build db query string
					$query = $db->prepare("INSERT INTO the_tool_survey (request_id, prop, value) VALUES (:request_id, :prop, :value);");
			
					$query->execute( array(':request_id' => $request_id, ':prop' => $property, ':value' => $value) );
				}
			}
		}
	}
	
	function getNoToolRequests($db) {
		$query = $db->prepare("SELECT COUNT(*) AS 'RequestNo' FROM the_tool_data");
			
		$query->execute();
		
		$result = $query->fetch(PDO::FETCH_ASSOC);
		
		return $result["RequestNo"];
	}
	
	function getNoSurveyFilled($db) {
		$query = $db->prepare("SELECT COUNT(id) AS 'SurveyNo' FROM the_tool_data WHERE survey = 1");
			
		$query->execute();
		
		$result = $query->fetch(PDO::FETCH_ASSOC);
		
		return $result["SurveyNo"];
	}
	
	function getSurveyStats($db, $property) {
		$query = $db->prepare( "SELECT
								  value AS 'ValueName',
								  COUNT(value) AS 'ValueCount'
								FROM
								  the_tool_survey
								WHERE
								  prop = :property
								GROUP BY
								  value
								ORDER BY
								  'ValueCount' DESC");
			
		$query->execute( array(":property" => $property) );
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getSurveyStatsPlainList($db, $property) {
		$query = $db->prepare( "SELECT
								  value AS 'ListItemText',
								  COUNT(value) AS 'ListItemCount'
								FROM
								  the_tool_survey
								WHERE
								  prop = :property
								GROUP BY
								  value
								ORDER BY
								  'ListItemCount' DESC");
			
		$query->execute( array(":property" => $property) );
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getOSStats($db) {
		$query = $db->prepare( "SELECT
								  os AS 'OperatingSystem',
								  COUNT(os) AS 'OSCount'
								FROM
								  the_tool_data
								GROUP BY
								  os
								ORDER BY
								  COUNT(os) DESC");
			
		$query->execute();
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getBrowserStats($db) {
		$query = $db->prepare( "SELECT
								  browser_name AS 'Browser',
								  COUNT(browser_name) AS 'BrowserCount'
								FROM
								  the_tool_data
								GROUP BY
								  browser_name
								ORDER BY
								  COUNT(browser_name) DESC");
				
		$query->execute();
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function transformChartData($data, $label, $value, $colors = array(), $translation = array()) {		
		if (!is_array($translation)) {
			$translation = array();
		}
	
		$arr = array();
		
		$idx = 0;
			
		foreach ($data as $tupel) {
			if (array_key_exists($tupel[$label], $translation)) {
				$display = $translation[$tupel[$label]];
			} else {
				$display = $tupel[$label];
			}
			
			array_push($arr, "{ value: " . $tupel[$value] . ", label: '" . $display . "', color: '" . $colors[$idx] . "' }");
			
			$idx++;
			
			if ($idx > count($colors)) {
				$idx = 0;
			}
		}
		
		return "[" . implode(",", $arr) . "]";
	}
	
	function displayChartDetails($data, $label, $value, $colors = array(), $translation = array()) {
		$html = "";
		
		$sum = 0;
		foreach ($data as $tupel) {
			$sum += $tupel[$value];
		}
		
		$idx = 0;
		
		$html .= "<table class='chart-detail'>";
			foreach ($data as $tupel) {
				$color_span = "<span class='stat-color-indicator' style='background-color: " . $colors[$idx] . ";'></span>";
				
				if (array_key_exists($tupel[$label], $translation)) {
					$display = $translation[$tupel[$label]];
				} else {
					$display = $tupel[$label];
				}
				
				$html .= "<tr>";
					$html .= "<td class='bold'>" . $color_span . $display . "</td>";
					$html .= "<td class='right'>" . $tupel[$value] . "</td>";
					$html .= "<td class='right grey'>" . round($tupel[$value] / $sum * 100, 0) . "%</td>";
				$html .= "</tr>";
				
				$idx++;
			
				if ($idx > count($colors)) {
					$idx = 0;
				}
			}
		$html .= "</table>";
		
		return $html;
	}
	
	function displayPlainList($data) {
		$html = "";
		
		$html .= "<table class='chart-detail'>";
			foreach($data as $tupel) {
				$html .= "<tr>";
					$html .= "<td class='bold'>" . $tupel["ListItemText"] . "</td>";
					$html .= "<td class='right'>" . $tupel["ListItemCount"] . "</td>";
				$html .= "</tr>";
			}
		$html .= "</table>";
		
		return $html;
	}
	
	function getPropertySum($db, $property) {
		$query = $db->prepare("SELECT SUM(value) AS 'PropertySum' FROM the_tool_details WHERE prop = :property GROUP BY prop");
			
		$query->execute( array(":property" => $property) );
		
		$result = $query->fetch(PDO::FETCH_ASSOC);
		
		return $result["PropertySum"];
	}
	
	function getPagedRequests($db, $top, $bottom) {
		$limit = $top . ", " . $bottom;
		
		$query = $db->prepare("SELECT
									d.id AS 'RequestId',
									d.timestamp AS 'RequestTimestamp',
									d.ip AS 'IpAddress',
									d.os AS 'OperatingSystem',
									d.browser_name AS 'BrowserName',
									d.browser_version AS 'BrowserVersion',
									d.mail AS 'SurveyContact',
									d.survey AS 'SurveyFilledIn'
								FROM
									the_tool_data d
								ORDER BY
									d.timestamp DESC
								LIMIT " . $limit);
			
		$query->execute();
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function setSurveyFlag($db, $request_id) {
		$query = $db->prepare("UPDATE the_tool_data SET survey = 1 WHERE id = :request_id");
			
		$query->execute( array(":request_id" => $request_id) );
	}
	
	function getRequestDetailsContent($db, $page, $size) {
		$html = "";
		
		$top = ($page - 1) * $size;
		$bottom = $page * $size;
		
		$requests = getPagedRequests($db, $top, $bottom);
		
		foreach ($requests as $request) {
			$html .= "<div class='stats-info-group-wrapper'>";
				$html .= "<div class='stats-info-group whole-row' data-id='" . $request["RequestId"] . "'>";
					// request timestamp
					$html .= "<span class='bold'>" . convertDatetime($request["RequestTimestamp"]) . "</span>";
					
					// container for country and survey spans
					$html .= "<span class='float-right'>";
						// survey span
						if ($request["SurveyFilledIn"] == 1) {
							$html .= "<span class='survey-indicator'>Survey</span>";
						}
						
						// country span
						$html .= "<span class='country-indicator' data-ip='" . $request["IpAddress"] . "'></span>";
					$html .= "</span>";
					
					$html .= "<div class='request-detail-wrapper'></div>";
				$html .= "</div>";
			$html .= "</div>";
		}
		
		return $html;
	}
	
	function getPaging($page, $size, $total) {
		$html = "";
		
		$pages_no = ceil($total / $size);
		
		$html .= "<div class='paging'>";
			// back indicator
			$display_back = $page == 1 ? "style='visibility: hidden;'" : "";
			
			$html .= "<span id='paging-back' class='paging-direction-indicator' data-direction='back' onclick='pagingClickHandler(\"back\");' " . $display_back . ">&lt;</span>";
			
			// page x of y
			$html .= "<span class='paging-location-indicator'><span id='paging-current'>" . $page . "</span> of <span id='paging-max'>" . $pages_no . "</span></span>";
			
			// forward indicator
			$display_forward = $page == $pages_no ? "style='visibility: hidden;'" : "";
			
			$html .= "<span id='paging-forward' class='paging-direction-indicator' data-direction='forward' onclick='pagingClickHandler(\"forward\");' " . $display_forward . ">&gt;</span>";
		$html .= "</div>";
		
		return $html;
	}
	
	function convertDatetime($datetime) {
		$parts = explode(" ", $datetime);
		
		$date = $parts[0];
		$time = $parts[1];
		
		$dateParts = explode("-", $date);
		
		return $dateParts[2] . "." . $dateParts[1] . "." . $dateParts[0] . " " . $time;
	}
	
	function getApiResponse($query) {
		$response = array();
		
		global $PAGING_SIZE;
		
		$api = $query["__api"];
		
		switch ($api) {
			case "result_details":
				$page = isset($query["page"]) ? $query["page"] : 1;
				$db = getDb();
				
				$response["data"] = getRequestDetailsContent($db, $page, $PAGING_SIZE);
				
				$response["status"] = "ok";
			
				break;
				
			case "request_details":
				$id = isset($query["id"]) ? $query["id"] : false;
			
				if ($id !== false) {
					$db = getDb();
					
					$raw_details = getRequestDetails($db, $id);
					$raw_survey = getSurveyDetails($db, $id);
					
					// try to get mail address
					$mail = getContactMailAddress($db, $id);
					
					// add categories to request details	
					$response["data"] = array();
					
					$response["data"]["request"] = getCategoriedDetails($raw_details);
					
					$merger = array();
					
					if ($mail != "") {
						array_push($merger, array("question" => "E-mail address", "answer" => $mail));
					}
					
					$categoried_survey = getCategoriedSurvey($raw_survey);
					
					$response["data"]["survey"] = array_merge($merger, $categoried_survey);
				
					$response["status"] = "ok";
				} else {
					$response["status"] = "error";
					$response["message"] = "no valid ID passed";
				}
			
				break;
				
			default:
				$response["status"] = "error";
				$response["message"] = "unknown API identifier";
				
				break;
		}
		
		return $response;
	}
	
	function getDb() {
		global $DATABASE_HOST;
		global $DATABASE_NAME;
		global $DATABASE_USER;
		global $DATABASE_PASSWORD;
		
		$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		
		return $db;
	}
	
	function getRequestDetails($db, $id) {
		$response = array();
		
		$query = $db->prepare("SELECT
								 prop AS 'SelectedProperty'
							   FROM
								 the_tool_details
							   WHERE
								 request_id = :request_id AND 
							     value = 1");
			
		$query->execute( array(':request_id' => $id) );
		
		$details = $query->fetchAll(PDO::FETCH_ASSOC);
		
		foreach ($details as $detail) {
			array_push($response, $detail["SelectedProperty"]);
		}
		
		return $response;
	}
	
	function getSurveyDetails($db, $id) {
		$query = $db->prepare("SELECT
								 prop AS 'Property',
								 value AS 'Value'
							   FROM
								 the_tool_survey
							   WHERE
								 request_id = :request_id");
			
		$query->execute( array(':request_id' => $id) );
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getCategoriedDetails($raw_details) {
		global $FIELDS_TOOL;
		global $KEY_PROPERTY;
		
		$details = array();
		
		// iterate over tool fields
		$categories = array_keys($FIELDS_TOOL);
		
		foreach ($categories as $category) {
			$elements = $FIELDS_TOOL[$category];
			
			$details[$category] = array();
			
			foreach ($elements as $element) {
				if (in_array($element[$KEY_PROPERTY], $raw_details)) {
					array_push($details[$category], $element);
				}
			}
		}
		
		return $details;
	}
	
	function getContactMailAddress($db, $request_id) {
		$query = $db->prepare("SELECT
								 mail AS 'SurveyContact'
							   FROM
								 the_tool_data
							   WHERE
								 id = :request_id");
			
		$query->execute( array(':request_id' => $request_id) );
		
		$result = $query->fetch(PDO::FETCH_ASSOC);
		
		return $result["SurveyContact"];
	}
	
	function getCategoriedSurvey($raw_survey) {
		global $FIELDS_SURVEY;
		global $KEY_DISPLAY;
		global $KEY_TRANSLATION;
		
		$details = array();
		
		// iterate over survey fields
		foreach ($raw_survey as $elem) {
			$element = array();
			
			$question = getSurveyQuestion($elem["Property"]);
			
			if ($question !== false) {
				$element["question"] = $question[$KEY_DISPLAY];
				
				if (is_array($question[$KEY_TRANSLATION])) {
					$element["answer"] = array_key_exists($elem["Value"], $question[$KEY_TRANSLATION]) ? $question[$KEY_TRANSLATION][$elem["Value"]] : $elem["Value"];
				} else {
					$element["answer"] = $elem["Value"];
				}
			}
			
			array_push($details, $element);
		}
		
		return $details;
	}