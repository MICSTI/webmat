<?php
	// Default time zone
	date_default_timezone_set("Europe/Vienna");
	
	// The tool constants
	$KEY_DISPLAY = "display";
	$KEY_PROPERTY = "property";
	$KEY_TRANSLATION = "translation";
	$KEY_TYPE = "type";
	
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
												array($KEY_PROPERTY => "purpose", $KEY_DISPLAY => "What is the purpose of your study?", $KEY_TYPE => "list-plain"),
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
												array($KEY_PROPERTY => "use", $KEY_DISPLAY => "Are you going to use the recommendations from the tool?", $KEY_TYPE => "doughnut", $KEY_TRANSLATION => array("yes" => "Yes", "no" => "No"))
											)
					);

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
								LIMIT " . $limit);
			
		$query->execute();
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function setSurveyFlag($db, $request_id) {
		$query = $db->prepare("UPDATE the_tool_data SET survey = 1 WHERE id = :request_id");
			
		$query->execute( array(":request_id" => $request_id) );
	}