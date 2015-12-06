<?php
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