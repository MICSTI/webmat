<?php
/**
 * Results page
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
 
 require('webmat-config.php');
 require('webmat-functions.php');

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
						<div class="entry-thumbnail">
							<?php the_post_thumbnail(); ?>
						</div>
						<?php endif; ?>

						
					</header><!-- .entry-header -->

					<div class="entry-content">
						
                        <?php
                            // Our own 
                            if ($_POST) {
                                // form fields
                                $children = isset($_POST["children"]) ? TRUE : FALSE;
                                $adolescents = isset($_POST["adolescents"]) ? TRUE : FALSE;
                                $adults = isset($_POST["adults"]) ? TRUE : FALSE;
                                $elderly = isset($_POST["elderly"]) ? TRUE : FALSE;

                                $general = isset($_POST["general"]) ? TRUE : FALSE;
                                $feeling = isset($_POST["feeling"]) ? TRUE : FALSE;
                                $life_satisfaction = isset($_POST["life-satisfaction"]) ? TRUE : FALSE;
                                $flourishing = isset($_POST["flourishing"]) ? TRUE : FALSE;
                                $resilience = isset($_POST["resilience"]) ? TRUE : FALSE;
                                $mindfulness = isset($_POST["mindfulness"]) ? TRUE : FALSE;
                                $self_esteem_efficacy = isset($_POST["self-esteem-efficacy"]) ? TRUE : FALSE;
                                $optimism = isset($_POST["optimism"]) ? TRUE : FALSE;
                                $meaning_purpose = isset($_POST["meaning-purpose"]) ? TRUE : FALSE;
                                $engagement = isset($_POST["engagement"]) ? TRUE : FALSE;
                                $autonomy = isset($_POST["autonomy"]) ? TRUE : FALSE;
                                $commitment = isset($_POST["commitment"]) ? TRUE : FALSE;
                                $competence = isset($_POST["competence"]) ? TRUE : FALSE;

                                $workplace = isset($_POST["workplace"]) ? TRUE : FALSE;

                                $items_single = isset($_POST["items-single"]) ? TRUE : FALSE;
                                $general_indicators = isset($_POST["general-indicators"]) ? TRUE : FALSE;
                                $items_2_10 = isset($_POST["items-2-10"]) ? TRUE : FALSE;
                                $items_11_20 = isset($_POST["items-11-20"]) ? TRUE : FALSE;
                                $items_21_30 = isset($_POST["items-21-30"]) ? TRUE : FALSE;
                                $items_30 = isset($_POST["items-30-+"]) ? TRUE : FALSE;

                                // age group
                                    $group_age = array();

                                    if ($children) {
										array_push($group_age, "(keyword_classification LIKE 'children' OR keyword_classification LIKE 'children/adolescents')");
                                    }

                                    if ($adolescents) {
										array_push($group_age, "(keyword_classification LIKE 'adolescents' OR keyword_classification LIKE 'children/adolescents')");
                                    }

                                    if ($adults) {
										array_push($group_age, "(keyword_classification LIKE 'adults')");
                                    }

                                    if ($elderly) {
										array_push($group_age, "(keyword_classification LIKE 'elderly population')");
                                    }

                                // Measure type
                                    $group_type = array();

                                    if ($general) {
										array_push($group_type, "(kw_hedonic_eudaimonic LIKE 'x')");
                                    }

                                    if ($feeling) {
										array_push($group_type, "(kw_happiness LIKE 'x' OR kw_pa_na LIKE 'x')");
                                    }

                                    if ($life_satisfaction) {
										array_push($group_type, "(kw_life_satisfaction LIKE 'x')");
                                    }
									
									if ($flourishing) {
										array_push($group_type, "(kw_flourishing LIKE 'x')");
                                    }

                                    if ($resilience) {
										array_push($group_type, "(kw_resilience LIKE 'x')");
                                    }

                                    if ($mindfulness) {
										array_push($group_type, "(kw_mindfulness LIKE 'x')");
                                    }

                                    if ($self_esteem_efficacy) {
										array_push($group_type, "(kw_self_esteem LIKE 'x' OR kw_self_efficacy LIKE 'x')");
                                    }

                                    if ($optimism) {
										array_push($group_type, "(kw_optimism LIKE 'x')");
                                    }

                                    if ($meaning_purpose) {
										array_push($group_type, "(kw_meaning_purpose LIKE 'x')");
                                    }

                                    if ($engagement) {
										array_push($group_type, "(kw_engagement LIKE 'x')");
                                    }

                                    if ($autonomy) {
										array_push($group_type, "(kw_autonomy LIKE 'x')");
                                    }

                                    if ($commitment) {
										array_push($group_type, "(kw_commitment LIKE 'x')");
                                    }

                                    if ($competence) {
										array_push($group_type, "(kw_competence LIKE 'x')");
                                    }                         
                                                            
                                // Work
                                    $group_work = array();

                                    if ($workplace) {
										array_push($group_work, "(kw_work_domain LIKE 'x')");
                                    }
                                
                                // Items
                                    $group_items = array();

                                    if ($items_single) {
										array_push($group_items, "(kw_single_item LIKE 'x')");
                                    }

                                    if ($general_indicators) {
										array_push($group_items, "(kw_general_indicators LIKE 'x')");
                                    }
                                    
                                    if ($items_2_10) {
										array_push($group_items, "(kw_items >= 2 AND kw_items <= 10)");
                                    }

                                    if ($items_11_20) {
										array_push($group_items, "(kw_items >= 11 AND kw_items <= 20)");
                                    }

                                    if ($items_21_30) {
										array_push($group_items, "(kw_items >= 21 AND kw_items <= 30)");
                                    }

                                    if ($items_30) {
										array_push($group_items, "(kw_items > 30)");
                                    }
                                
                                // build where query
                                $where = array();
								
								$glue_within_group = " OR ";
								$glue_outside_group = " AND ";
								
								if (count($group_age) > 0) {
									array_push($where, "(" . implode($glue_within_group, $group_age) . ")");
								}
								
								if (count($group_type) > 0) {
									array_push($where, "(" . implode($glue_within_group, $group_type) . ")");
								}
								
								if (count($group_work) > 0) {
									array_push($where, "(" . implode($glue_within_group, $group_work) . ")");
								}
								
								if (count($group_items) > 0) {
									array_push($where, "(" . implode($glue_within_group, $group_items) . ")");
								}

								if (count($where) > 0) {
									$where_query = implode($glue_outside_group, $where);
									
									$where_query .= " AND active = 1";
									
									// set up PDO database connection
									try {
										$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASSWORD);
										$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
										$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
										
										// save request to database
										$ip = $_SERVER['REMOTE_ADDR'];
										
										// request meta data
										$request_id = addRequestMetaData($db, $ip);
										
										// request details
										addRequestDetails($db, $request_id, $_POST);

										$query = $db->prepare("SELECT name, kw_single_item, kw_general_indicators, kw_items, abstract, keywords, example_items, original_study_details, secondary_study_details FROM measure_data WHERE " . $where_query);
										$query->execute();

										$rows = $query->rowCount();

										if ($rows > 0) {
											//echo "<h1 class='entry-title'>Results</h1>";
											echo "<div class='webmat-title'>Results</div>";
											echo "<div class='results-title'>Here are your appropriate results listed. Please click on each measurement for further information.</div>";
											
											$count = 1;

											$results = $query->fetchAll(PDO::FETCH_ASSOC);

											foreach ($results as $row) {
												$id = "webmat-" . $count;
												
												$no_items = $row["kw_items"];
												
												echo "<div class='result'>";
													// add request id as a hidden field
													echo "<input type='hidden' id='request-id' value='" . $request_id . "' />";
												
													echo "<div class='result-title' onclick=\"toggleDisplay('" . $id . "')\">";
														echo  "<span id='" . $id . "-plus' class='detail-indicator detail-indicator-plus'>+</span>";
														echo  "<span id='" . $id . "-minus' class='detail-indicator detail-indicator-minus'>-</span>";
														
														echo  "<span>" . $row["name"] . "</span>";
													echo "</div>";
													
													echo "<div class='result-details' id='" . $id . "'>";
														echo "<div>" . $row["abstract"] . "</div>";
														echo "<div>" . $row["keywords"] . "</div>";
														
														$single_item = $row["kw_single_item"];
														$general_indicators = $row["kw_general_indicators"];
														
														if (!($single_item == "x" OR $general_indicators == "x")) {
															if (!empty($no_items)) {
																echo "<div>This scale includes " . $no_items . " items.</div>";
															}
															
															$example_items = $row["example_items"];
															
															if (!empty($example_items)) {
																echo "<div>Example items: " . $example_items . "</div>";
															}
														}
														
														$study_details = empty($row["original_study_details"]) ? $row["secondary_study_details"] : $row["original_study_details"];
														echo "<div>" . $study_details . "</div>";
													echo "</div>";
												echo "</div>";
												
												$count++;
											}
										} else {
											echo "<div class='no-results-found'>Sorry, we couldn't find any results which are suitable for your selection.</div>";
										}
									} catch(PDOException $ex) {
										echo "An error occurred during the database query!\n";
										echo $ex;
									}
									
									// build javascript function block
									$html = "";
									$html .= "<script type='text/javascript'>";
										$html .= "function toggleDisplay(_id) { ";
											$html .= "var elem = document.getElementById(_id);";
											$html .= "var plus = document.getElementById(_id + '-plus');";
											$html .= "var minus = document.getElementById(_id + '-minus');";
											
											$html .= "if (elem.style.display == 'block') {";
												$html .= "elem.style.display = 'none';";
												
												$html .= "if (plus) {";
													$html .= "plus.style.display = 'inline-block';";
													$html .= "minus.style.display = 'none';";
												$html .= "}";
											$html .= "} else {";
												$html .= "elem.style.display = 'block';";
												
												$html .= "if (plus) {";
													$html .= "plus.style.display = 'none';";
													$html .= "minus.style.display = 'inline-block';";
												$html .= "}";
												
												// auto-focus
												//$html .= "jQuery('#' + _id + ' input.focus').focus();";
											$html .= "}";
											
										$html .= " }";
									$html .= "</script>";
									
									echo $html;
									
									/*echo "<script type='text/javascript'>";
										echo "function toggleDisplay(_id) { var elem = document.getElementById(_id); elem.style.display = elem.style.display == 'block' ? 'none' : 'block'; }";
									echo "</script>";*/
								}
                            } else {
								echo "<div class='no-results-found'>Sorry, but you didn't select any box.</div>";
							}
                        ?>
						
						<section class="webmat-survey-container">
							<form action="/survey" method="post">
								<div class="survey-paragraph">
									<div id="survey-content-toggle" class="survey-title" onclick="toggleDisplay('survey-content')">
										Do you mind telling us something about you and your study?<br/>Please take a moment, click here and fill out the questions below.
									</div>
								</div>
								
								<div id="survey-content">
									<div class="survey-paragraph">
										<div class="survey-paragraph-title" onclick="toggleDisplay('p-helpful')">
											Have the suggestions been helpful for choosing a measurement?
										</div>
										
										<div class="survey-paragraph-question" id="p-helpful">
											<div><input name="q-helpful" type="radio" value="yes" id="helpful-yes" /><label for="helpful-yes"> Yes, I know now better which measurement I can use.</label></div>
											<div><input name="q-helpful" type="radio" value="no" id="helpful-no" /><label for="helpful-no"> No, I still don't know which measurement I can use.</label></div>
										</div>
									</div>
									
									<div class="survey-paragraph">
										<div class="survey-paragraph-title" onclick="toggleDisplay('p-purpose')">
											What is the purpose of your study?
										</div>
										
										<div class="survey-paragraph-question" id="p-purpose">
											<input id="q-purpose" name="q-purpose" type="text" class="focus" size="40"/>
										</div>
									</div>
									
									<div class="survey-paragraph">
										<div class="survey-paragraph-title" onclick="toggleDisplay('p-occupation')">
											Are you a...
										</div>
										
										<div class="survey-paragraph-question" id="p-occupation">
											<div><input name="q-occupation" type="radio" value="student" id="o-student" /><label for="o-student"> Student</label></div>
											<div><input name="q-occupation" type="radio" value="researcher" id="o-researcher" /><label for="o-researcher"> Researcher (including PhD)</label></div>
											<div><input name="q-occupation" type="radio" value="professor_academic" id="o-professor" /><label for="o-professor"> Professor/Academic</label></div>
											<div><input name="q-occupation" type="radio" value="policy_maker" id="o-policy-maker" /><label for="o-policy-maker"> Policy Maker</label></div>
											<div><input name="q-occupation" type="radio" value="health_professional" id="o-health" /><label for="o-health"> Health Professional</label></div>
											<div><input name="q-occupation" type="radio" value="volunteer" id="o-volunteer" /><label for="o-volunteer"> Volunteer</label></div>
											<div><input name="q-occupation" type="radio" value="charity_worker" id="o-charity" /><label for="o-charity"> Charity Worker</label></div>
											<div><input name="q-occupation" type="radio" value="other" id="o-other" onclick="jQuery('#q-occupation-other').focus();" /><label for="o-other" onclick="jQuery('#q-occupation-other').focus();"> Other</label></div>
											<div><input id="q-occupation-other" name="q-occupation-other" type="text" size="40"/></div>
										</div>
									</div>
									
									<div class="survey-paragraph">
										<div class="survey-paragraph-title" onclick="toggleDisplay('p-country')">
											In which country are you working?
										</div>
										
										<div class="survey-paragraph-question" id="p-country">
											<select id="q-country" name="q-country">
												<option value="default">Please select below...</option>
												<option value="Afganistan">Afghanistan</option>
												<option value="Albania">Albania</option>
												<option value="Algeria">Algeria</option>
												<option value="American Samoa">American Samoa</option>
												<option value="Andorra">Andorra</option>
												<option value="Angola">Angola</option>
												<option value="Anguilla">Anguilla</option>
												<option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
												<option value="Argentina">Argentina</option>
												<option value="Armenia">Armenia</option>
												<option value="Aruba">Aruba</option>
												<option value="Australia">Australia</option>
												<option value="Austria">Austria</option>
												<option value="Azerbaijan">Azerbaijan</option>
												<option value="Bahamas">Bahamas</option>
												<option value="Bahrain">Bahrain</option>
												<option value="Bangladesh">Bangladesh</option>
												<option value="Barbados">Barbados</option>
												<option value="Belarus">Belarus</option>
												<option value="Belgium">Belgium</option>
												<option value="Belize">Belize</option>
												<option value="Benin">Benin</option>
												<option value="Bermuda">Bermuda</option>
												<option value="Bhutan">Bhutan</option>
												<option value="Bolivia">Bolivia</option>
												<option value="Bonaire">Bonaire</option>
												<option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option>
												<option value="Botswana">Botswana</option>
												<option value="Brazil">Brazil</option>
												<option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
												<option value="Brunei">Brunei</option>
												<option value="Bulgaria">Bulgaria</option>
												<option value="Burkina Faso">Burkina Faso</option>
												<option value="Burundi">Burundi</option>
												<option value="Cambodia">Cambodia</option>
												<option value="Cameroon">Cameroon</option>
												<option value="Canada">Canada</option>
												<option value="Canary Islands">Canary Islands</option>
												<option value="Cape Verde">Cape Verde</option>
												<option value="Cayman Islands">Cayman Islands</option>
												<option value="Central African Republic">Central African Republic</option>
												<option value="Chad">Chad</option>
												<option value="Channel Islands">Channel Islands</option>
												<option value="Chile">Chile</option>
												<option value="China">China</option>
												<option value="Christmas Island">Christmas Island</option>
												<option value="Cocos Island">Cocos Island</option>
												<option value="Colombia">Colombia</option>
												<option value="Comoros">Comoros</option>
												<option value="Congo">Congo</option>
												<option value="Cook Islands">Cook Islands</option>
												<option value="Costa Rica">Costa Rica</option>
												<option value="Cote DIvoire">Cote D'Ivoire</option>
												<option value="Croatia">Croatia</option>
												<option value="Cuba">Cuba</option>
												<option value="Curaco">Curacao</option>
												<option value="Cyprus">Cyprus</option>
												<option value="Czech Republic">Czech Republic</option>
												<option value="Denmark">Denmark</option>
												<option value="Djibouti">Djibouti</option>
												<option value="Dominica">Dominica</option>
												<option value="Dominican Republic">Dominican Republic</option>
												<option value="East Timor">East Timor</option>
												<option value="Ecuador">Ecuador</option>
												<option value="Egypt">Egypt</option>
												<option value="El Salvador">El Salvador</option>
												<option value="Equatorial Guinea">Equatorial Guinea</option>
												<option value="Eritrea">Eritrea</option>
												<option value="Estonia">Estonia</option>
												<option value="Ethiopia">Ethiopia</option>
												<option value="Falkland Islands">Falkland Islands</option>
												<option value="Faroe Islands">Faroe Islands</option>
												<option value="Fiji">Fiji</option>
												<option value="Finland">Finland</option>
												<option value="France">France</option>
												<option value="French Guiana">French Guiana</option>
												<option value="French Polynesia">French Polynesia</option>
												<option value="French Southern Ter">French Southern Ter</option>
												<option value="Gabon">Gabon</option>
												<option value="Gambia">Gambia</option>
												<option value="Georgia">Georgia</option>
												<option value="Germany">Germany</option>
												<option value="Ghana">Ghana</option>
												<option value="Gibraltar">Gibraltar</option>
												<option value="Great Britain">Great Britain</option>
												<option value="Greece">Greece</option>
												<option value="Greenland">Greenland</option>
												<option value="Grenada">Grenada</option>
												<option value="Guadeloupe">Guadeloupe</option>
												<option value="Guam">Guam</option>
												<option value="Guatemala">Guatemala</option>
												<option value="Guinea">Guinea</option>
												<option value="Guyana">Guyana</option>
												<option value="Haiti">Haiti</option>
												<option value="Hawaii">Hawaii</option>
												<option value="Honduras">Honduras</option>
												<option value="Hong Kong">Hong Kong</option>
												<option value="Hungary">Hungary</option>
												<option value="Iceland">Iceland</option>
												<option value="India">India</option>
												<option value="Indonesia">Indonesia</option>
												<option value="Iran">Iran</option>
												<option value="Iraq">Iraq</option>
												<option value="Ireland">Ireland</option>
												<option value="Isle of Man">Isle of Man</option>
												<option value="Israel">Israel</option>
												<option value="Italy">Italy</option>
												<option value="Jamaica">Jamaica</option>
												<option value="Japan">Japan</option>
												<option value="Jordan">Jordan</option>
												<option value="Kazakhstan">Kazakhstan</option>
												<option value="Kenya">Kenya</option>
												<option value="Kiribati">Kiribati</option>
												<option value="Korea North">Korea North</option>
												<option value="Korea Sout">Korea South</option>
												<option value="Kuwait">Kuwait</option>
												<option value="Kyrgyzstan">Kyrgyzstan</option>
												<option value="Laos">Laos</option>
												<option value="Latvia">Latvia</option>
												<option value="Lebanon">Lebanon</option>
												<option value="Lesotho">Lesotho</option>
												<option value="Liberia">Liberia</option>
												<option value="Libya">Libya</option>
												<option value="Liechtenstein">Liechtenstein</option>
												<option value="Lithuania">Lithuania</option>
												<option value="Luxembourg">Luxembourg</option>
												<option value="Macau">Macau</option>
												<option value="Macedonia">Macedonia</option>
												<option value="Madagascar">Madagascar</option>
												<option value="Malaysia">Malaysia</option>
												<option value="Malawi">Malawi</option>
												<option value="Maldives">Maldives</option>
												<option value="Mali">Mali</option>
												<option value="Malta">Malta</option>
												<option value="Marshall Islands">Marshall Islands</option>
												<option value="Martinique">Martinique</option>
												<option value="Mauritania">Mauritania</option>
												<option value="Mauritius">Mauritius</option>
												<option value="Mayotte">Mayotte</option>
												<option value="Mexico">Mexico</option>
												<option value="Midway Islands">Midway Islands</option>
												<option value="Moldova">Moldova</option>
												<option value="Monaco">Monaco</option>
												<option value="Mongolia">Mongolia</option>
												<option value="Montserrat">Montserrat</option>
												<option value="Morocco">Morocco</option>
												<option value="Mozambique">Mozambique</option>
												<option value="Myanmar">Myanmar</option>
												<option value="Nambia">Nambia</option>
												<option value="Nauru">Nauru</option>
												<option value="Nepal">Nepal</option>
												<option value="Netherland Antilles">Netherland Antilles</option>
												<option value="Netherlands">Netherlands</option>
												<option value="Nevis">Nevis</option>
												<option value="New Caledonia">New Caledonia</option>
												<option value="New Zealand">New Zealand</option>
												<option value="Nicaragua">Nicaragua</option>
												<option value="Niger">Niger</option>
												<option value="Nigeria">Nigeria</option>
												<option value="Niue">Niue</option>
												<option value="Norfolk Island">Norfolk Island</option>
												<option value="Norway">Norway</option>
												<option value="Oman">Oman</option>
												<option value="Pakistan">Pakistan</option>
												<option value="Palau Island">Palau Island</option>
												<option value="Palestine">Palestine</option>
												<option value="Panama">Panama</option>
												<option value="Papua New Guinea">Papua New Guinea</option>
												<option value="Paraguay">Paraguay</option>
												<option value="Peru">Peru</option>
												<option value="Phillipines">Philippines</option>
												<option value="Pitcairn Island">Pitcairn Island</option>
												<option value="Poland">Poland</option>
												<option value="Portugal">Portugal</option>
												<option value="Puerto Rico">Puerto Rico</option>
												<option value="Qatar">Qatar</option>
												<option value="Republic of Montenegro">Republic of Montenegro</option>
												<option value="Republic of Serbia">Republic of Serbia</option>
												<option value="Reunion">Reunion</option>
												<option value="Romania">Romania</option>
												<option value="Russia">Russia</option>
												<option value="Rwanda">Rwanda</option>
												<option value="St Barthelemy">St Barthelemy</option>
												<option value="St Eustatius">St Eustatius</option>
												<option value="St Helena">St Helena</option>
												<option value="St Kitts-Nevis">St Kitts-Nevis</option>
												<option value="St Lucia">St Lucia</option>
												<option value="St Maarten">St Maarten</option>
												<option value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon</option>
												<option value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines</option>
												<option value="Saipan">Saipan</option>
												<option value="Samoa">Samoa</option>
												<option value="Samoa American">Samoa American</option>
												<option value="San Marino">San Marino</option>
												<option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe</option>
												<option value="Saudi Arabia">Saudi Arabia</option>
												<option value="Senegal">Senegal</option>
												<option value="Serbia">Serbia</option>
												<option value="Seychelles">Seychelles</option>
												<option value="Sierra Leone">Sierra Leone</option>
												<option value="Singapore">Singapore</option>
												<option value="Slovakia">Slovakia</option>
												<option value="Slovenia">Slovenia</option>
												<option value="Solomon Islands">Solomon Islands</option>
												<option value="Somalia">Somalia</option>
												<option value="South Africa">South Africa</option>
												<option value="Spain">Spain</option>
												<option value="Sri Lanka">Sri Lanka</option>
												<option value="Sudan">Sudan</option>
												<option value="Suriname">Suriname</option>
												<option value="Swaziland">Swaziland</option>
												<option value="Sweden">Sweden</option>
												<option value="Switzerland">Switzerland</option>
												<option value="Syria">Syria</option>
												<option value="Tahiti">Tahiti</option>
												<option value="Taiwan">Taiwan</option>
												<option value="Tajikistan">Tajikistan</option>
												<option value="Tanzania">Tanzania</option>
												<option value="Thailand">Thailand</option>
												<option value="Togo">Togo</option>
												<option value="Tokelau">Tokelau</option>
												<option value="Tonga">Tonga</option>
												<option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
												<option value="Tunisia">Tunisia</option>
												<option value="Turkey">Turkey</option>
												<option value="Turkmenistan">Turkmenistan</option>
												<option value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option>
												<option value="Tuvalu">Tuvalu</option>
												<option value="Uganda">Uganda</option>
												<option value="Ukraine">Ukraine</option>
												<option value="United Arab Erimates">United Arab Emirates</option>
												<option value="United Kingdom">United Kingdom</option>
												<option value="United States of America">United States of America</option>
												<option value="Uraguay">Uruguay</option>
												<option value="Uzbekistan">Uzbekistan</option>
												<option value="Vanuatu">Vanuatu</option>
												<option value="Vatican City State">Vatican City State</option>
												<option value="Venezuela">Venezuela</option>
												<option value="Vietnam">Vietnam</option>
												<option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
												<option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
												<option value="Wake Island">Wake Island</option>
												<option value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option>
												<option value="Yemen">Yemen</option>
												<option value="Zaire">Zaire</option>
												<option value="Zambia">Zambia</option>
												<option value="Zimbabwe">Zimbabwe</option>
												<option value="Other">Other</option>
											</select>
										</div>
									</div>

									<div class="survey-paragraph">
										<div class="survey-paragraph-title" onclick="toggleDisplay('p-nature')">
											What is the nature of work you are doing?
										</div>
										
										<div class="survey-paragraph-question" id="p-nature">
											<select id="q-nature" name="q-nature">
												<option value="default">Please select below...</option>
												<option value="clinical">Clinical setting</option>
												<option value="industrial_organisational">Industrial-organisational setting</option>
												<option value="health">Health setting</option>
												<option value="school">School setting</option>
												<option value="political">Political setting</option>
												<option value="other">Other</option>
											</select>
										</div>
									</div>
									
									<div class="survey-paragraph">
										<div class="survey-paragraph-title" onclick="toggleDisplay('p-based')">
											Where are you currently based?
										</div>
										
										<div class="survey-paragraph-question" id="p-based">
											<input id="q-based" name="q-based" type="text" class="focus" size="40"/>
										</div>
									</div>
									
									<div class="survey-paragraph">
										<div class="survey-paragraph-title" onclick="toggleDisplay('p-funded')">
											Is your work funded?
										</div>
										
										<div class="survey-paragraph-question" id="p-funded">
											<div><input name="q-funded" type="radio" value="yes" id="funded-yes" /><label for="funded-yes"> Yes</label></div>
											<div><input name="q-funded" type="radio" value="no" id="funded-no" /><label for="funded-no"> No</label></div>
										</div>
									</div>
									
									<div class="survey-paragraph">
										<div class="survey-paragraph-title" onclick="toggleDisplay('p-reco')">
											Are you going to use the recommendations from the tool?
										</div>
										
										<div class="survey-paragraph-question" id="p-reco">
											<div><input name="q-use" type="radio" value="yes" id="use-yes" /><label for="use-yes"> Yes</label></div>
											<div><input name="q-use" type="radio" value="no" id="use-no" /><label for="use-no"> No</label></div>
										</div>
									</div>
									
									<div class="survey-paragraph">
										<div class="survey-paragraph-title" >
											Leave your email for follow up.
										</div>
										
										<div>
											<input id="q-email" name="q-email" type="text" size="40"/>
										</div>
									</div>	
									
									<div class="survey-paragraph">
										<div class="survey-title">
											Thank you very much for your help!
										</div>
										
										<div>
											<input type="submit" class="webmat-button" value="Send" />
										</div>
									</div>
								</div>
							</form>
						</section>

                        <div id="button-back">
							<button class="webmat-button webmat-results" onclick="window.location.href='../the-tool/#question-1'">Back to the start</button> 
	                    </div>


						<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentythirteen' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
					</div><!-- .entry-content -->

					<footer class="entry-meta">
						<?php edit_post_link( __( 'Edit', 'twentythirteen' ), '<span class="edit-link">', '</span>' ); ?>
					</footer><!-- .entry-meta -->
				</article><!-- #post -->

				<?php comments_template(); ?>
			<?php endwhile; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>