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
                                $items_30 = isset($_POST["items-30+"]) ? TRUE : FALSE;

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
											<option id="QR~QID5~2" class="Selection" value="2">Afghanistan</option>
											<option id="QR~QID5~3" class="Selection" value="3">Albania</option>
											<option id="QR~QID5~4" class="Selection" value="4">Algeria</option>
											<option id="QR~QID5~5" class="Selection" value="5">Andorra</option>
											<option id="QR~QID5~6" class="Selection" value="6">Angola</option>
											<option id="QR~QID5~7" class="Selection" value="7">Antigua and Barbuda</option>
											<option id="QR~QID5~8" class="Selection" value="8">Argentina</option>
											<option id="QR~QID5~9" class="Selection" value="9">Armenia</option>
											<option id="QR~QID5~10" class="Selection" value="10">Australia</option>
											<option id="QR~QID5~11" class="Selection" value="11">Austria</option>
											<option id="QR~QID5~12" class="Selection" value="12">Azerbaijan</option>
											<option id="QR~QID5~13" class="Selection" value="13">Bahamas</option>
											<option id="QR~QID5~14" class="Selection" value="14">Bahrain</option>
											<option id="QR~QID5~15" class="Selection" value="15">Bangladesh</option>
											<option id="QR~QID5~16" class="Selection" value="16">Barbados</option>
											<option id="QR~QID5~17" class="Selection" value="17">Belarus</option>
											<option id="QR~QID5~18" class="Selection" value="18">Belgium</option>
											<option id="QR~QID5~19" class="Selection" value="19">Belize</option>
											<option id="QR~QID5~20" class="Selection" value="20">Benin</option>
											<option id="QR~QID5~21" class="Selection" value="21">Bhutan</option>
											<option id="QR~QID5~22" class="Selection" value="22">Bolivia</option>
											<option id="QR~QID5~23" class="Selection" value="23">Bosnia and Herzegovina</option>
											<option id="QR~QID5~24" class="Selection" value="24">Botswana</option>
											<option id="QR~QID5~25" class="Selection" value="25">Brazil</option>
											<option id="QR~QID5~26" class="Selection" value="26">Brunei</option>
											<option id="QR~QID5~27" class="Selection" value="27">Bulgaria</option>
											<option id="QR~QID5~28" class="Selection" value="28">Burkina Faso</option>
											<option id="QR~QID5~29" class="Selection" value="29">Burma/Myanmar</option>
											<option id="QR~QID5~30" class="Selection" value="30">Burundi</option>
											<option id="QR~QID5~31" class="Selection" value="31">Cambodia</option>
											<option id="QR~QID5~32" class="Selection" value="32">Cameroon</option>
											<option id="QR~QID5~33" class="Selection" value="33">Canada</option>
											<option id="QR~QID5~34" class="Selection" value="34">Cape Verde</option>
											<option id="QR~QID5~35" class="Selection" value="35">Central African Republic</option>
											<option id="QR~QID5~36" class="Selection" value="36">Chad</option>
											<option id="QR~QID5~37" class="Selection" value="37">Chile</option>
											<option id="QR~QID5~38" class="Selection" value="38">China</option>
											<option id="QR~QID5~39" class="Selection" value="39">Colombia</option>
											<option id="QR~QID5~40" class="Selection" value="40">Comoros</option>
											<option id="QR~QID5~41" class="Selection" value="41">Congo</option>
											<option id="QR~QID5~42" class="Selection" value="42">Congo, Democratic Republic of</option>
											<option id="QR~QID5~43" class="Selection" value="43">Costa Rica</option>
											<option id="QR~QID5~44" class="Selection" value="44">Cote d'Ivoire/Ivory Coast</option>
											<option id="QR~QID5~45" class="Selection" value="45">Croatia</option>
											<option id="QR~QID5~46" class="Selection" value="46">Cuba</option>
											<option id="QR~QID5~47" class="Selection" value="47">Cyprus</option>
											<option id="QR~QID5~48" class="Selection" value="48">Czech Republic</option>
											<option id="QR~QID5~49" class="Selection" value="49">Denmark</option>
											<option id="QR~QID5~50" class="Selection" value="50">Djibouti</option>
											<option id="QR~QID5~51" class="Selection" value="51">Dominica</option>
											<option id="QR~QID5~52" class="Selection" value="52">Dominican Republic</option>
											<option id="QR~QID5~53" class="Selection" value="53">East Timor</option>
											<option id="QR~QID5~54" class="Selection" value="54">Ecuador</option>
											<option id="QR~QID5~55" class="Selection" value="55">Egypt</option>
											<option id="QR~QID5~56" class="Selection" value="56">El Salvador</option>
											<option id="QR~QID5~57" class="Selection" value="57">Equatorial Guinea</option>
											<option id="QR~QID5~58" class="Selection" value="58">Eritrea</option>
											<option id="QR~QID5~59" class="Selection" value="59">Estonia</option>
											<option id="QR~QID5~60" class="Selection" value="60">Ethiopia Fiji</option>
											<option id="QR~QID5~61" class="Selection" value="61">Finland</option>
											<option id="QR~QID5~62" class="Selection" value="62">France</option>
											<option id="QR~QID5~63" class="Selection" value="63">Gabon</option>
											<option id="QR~QID5~64" class="Selection" value="64">Gambia</option>
											<option id="QR~QID5~65" class="Selection" value="65">Georgia</option>
											<option id="QR~QID5~66" class="Selection" value="66">Germany</option>
											<option id="QR~QID5~67" class="Selection" value="67">Ghana</option>
											<option id="QR~QID5~68" class="Selection" value="68">Greece</option>
											<option id="QR~QID5~69" class="Selection" value="69">Grenada</option>
											<option id="QR~QID5~70" class="Selection" value="70">Guatemala</option>
											<option id="QR~QID5~71" class="Selection" value="71">Guinea</option>
											<option id="QR~QID5~72" class="Selection" value="72">Guinea-Bissau (Bissau) (AF)</option>
											<option id="QR~QID5~73" class="Selection" value="73">Guyana</option>
											<option id="QR~QID5~74" class="Selection" value="74">Haiti</option>
											<option id="QR~QID5~75" class="Selection" value="75">Honduras</option>
											<option id="QR~QID5~76" class="Selection" value="76">Hungary</option>
											<option id="QR~QID5~77" class="Selection" value="77">Iceland</option>
											<option id="QR~QID5~78" class="Selection" value="78">India</option>
											<option id="QR~QID5~79" class="Selection" value="79">Indonesia</option>
											<option id="QR~QID5~80" class="Selection" value="80">Iran</option>
											<option id="QR~QID5~81" class="Selection" value="81">Iraq</option>
											<option id="QR~QID5~82" class="Selection" value="82">Ireland</option>
											<option id="QR~QID5~83" class="Selection" value="83">Israel</option>
											<option id="QR~QID5~84" class="Selection" value="84">Italy</option>
											<option id="QR~QID5~85" class="Selection" value="85">Jamaica</option>
											<option id="QR~QID5~86" class="Selection" value="86">Japan</option>
											<option id="QR~QID5~87" class="Selection" value="87">Jordan</option>
											<option id="QR~QID5~88" class="Selection" value="88">Kazakstan</option>
											<option id="QR~QID5~89" class="Selection" value="89">Kenya</option>
											<option id="QR~QID5~90" class="Selection" value="90">Kiribati</option>
											<option id="QR~QID5~91" class="Selection" value="91">Korea, North</option>
											<option id="QR~QID5~92" class="Selection" value="92">Korea, South</option>
											<option id="QR~QID5~93" class="Selection" value="93">Kuwait</option>
											<option id="QR~QID5~94" class="Selection" value="94">Kyrgyzstan</option>
											<option id="QR~QID5~95" class="Selection" value="95">Laos</option>
											<option id="QR~QID5~96" class="Selection" value="96">Latvia</option>
											<option id="QR~QID5~97" class="Selection" value="97">Lebanon</option>
											<option id="QR~QID5~98" class="Selection" value="98">Lesotho</option>
											<option id="QR~QID5~99" class="Selection" value="99">Liberia</option>
											<option id="QR~QID5~100" class="Selection" value="100">Libya</option>
											<option id="QR~QID5~101" class="Selection" value="101">Liechtenstein</option>
											<option id="QR~QID5~102" class="Selection" value="102">Lithuania</option>
											<option id="QR~QID5~103" class="Selection" value="103">Luxembourg</option>
											<option id="QR~QID5~104" class="Selection" value="104">Macedonia</option>
											<option id="QR~QID5~105" class="Selection" value="105">Madagascar</option>
											<option id="QR~QID5~106" class="Selection" value="106">Malawi</option>
											<option id="QR~QID5~107" class="Selection" value="107">Malaysia</option>
											<option id="QR~QID5~108" class="Selection" value="108">Maldives</option>
											<option id="QR~QID5~109" class="Selection" value="109">Mali</option>
											<option id="QR~QID5~110" class="Selection" value="110">Malta</option>
											<option id="QR~QID5~111" class="Selection" value="111">Marshall Islands</option>
											<option id="QR~QID5~112" class="Selection" value="112">Mauritania</option>
											<option id="QR~QID5~113" class="Selection" value="113">Mauritius</option>
											<option id="QR~QID5~114" class="Selection" value="114">Mexico</option>
											<option id="QR~QID5~115" class="Selection" value="115">Micronesia</option>
											<option id="QR~QID5~116" class="Selection" value="116">Moldova</option>
											<option id="QR~QID5~117" class="Selection" value="117">Monaco</option>
											<option id="QR~QID5~118" class="Selection" value="118">Mongolia</option>
											<option id="QR~QID5~119" class="Selection" value="119">Montenegro</option>
											<option id="QR~QID5~120" class="Selection" value="120">Morocco</option>
											<option id="QR~QID5~121" class="Selection" value="121">Mozambique</option>
											<option id="QR~QID5~122" class="Selection" value="122">Namibia</option>
											<option id="QR~QID5~123" class="Selection" value="123">Nauru</option>
											<option id="QR~QID5~124" class="Selection" value="124">Nepal</option>
											<option id="QR~QID5~125" class="Selection" value="125">Netherlands</option>
											<option id="QR~QID5~126" class="Selection" value="126">New Zealand</option>
											<option id="QR~QID5~127" class="Selection" value="127">Nicaragua</option>
											<option id="QR~QID5~128" class="Selection" value="128">Niger</option>
											<option id="QR~QID5~129" class="Selection" value="129">Nigeria</option>
											<option id="QR~QID5~130" class="Selection" value="130">Norway</option>
											<option id="QR~QID5~131" class="Selection" value="131">Oman</option>
											<option id="QR~QID5~132" class="Selection" value="132">Pakistan</option>
											<option id="QR~QID5~133" class="Selection" value="133">Palau</option>
											<option id="QR~QID5~134" class="Selection" value="134">Panama</option>
											<option id="QR~QID5~135" class="Selection" value="135">Papua New Guinea</option>
											<option id="QR~QID5~136" class="Selection" value="136">Paraguay</option>
											<option id="QR~QID5~137" class="Selection" value="137">Peru</option>
											<option id="QR~QID5~138" class="Selection" value="138">Philippines</option>
											<option id="QR~QID5~139" class="Selection" value="139">Poland</option>
											<option id="QR~QID5~140" class="Selection" value="140">Portugal</option>
											<option id="QR~QID5~141" class="Selection" value="141">Qatar</option>
											<option id="QR~QID5~142" class="Selection" value="142">Romania</option>
											<option id="QR~QID5~143" class="Selection" value="143">Russian Federation</option>
											<option id="QR~QID5~144" class="Selection" value="144">Rwanda</option>
											<option id="QR~QID5~145" class="Selection" value="145">Saint Kitts and Nevis</option>
											<option id="QR~QID5~146" class="Selection" value="146">Saint Lucia</option>
											<option id="QR~QID5~147" class="Selection" value="147">Saint Vincent and the Grenadines</option>
											<option id="QR~QID5~148" class="Selection" value="148">Samoa</option>
											<option id="QR~QID5~149" class="Selection" value="149">San Marino</option>
											<option id="QR~QID5~150" class="Selection" value="150">Sao Tome and Principe</option>
											<option id="QR~QID5~151" class="Selection" value="151">Saudi Arabia</option>
											<option id="QR~QID5~152" class="Selection" value="152">Senegal</option>
											<option id="QR~QID5~153" class="Selection" value="153">Serbia</option>
											<option id="QR~QID5~154" class="Selection" value="154">Seychelles</option>
											<option id="QR~QID5~155" class="Selection" value="155">Sierra Leone</option>
											<option id="QR~QID5~156" class="Selection" value="156">Singapore</option>
											<option id="QR~QID5~157" class="Selection" value="157">Slovakia</option>
											<option id="QR~QID5~158" class="Selection" value="158">Slovenia</option>
											<option id="QR~QID5~159" class="Selection" value="159">Solomon Islands</option>
											<option id="QR~QID5~160" class="Selection" value="160">Somalia</option>
											<option id="QR~QID5~161" class="Selection" value="161">South Africa</option>
											<option id="QR~QID5~162" class="Selection" value="162">Spain</option>
											<option id="QR~QID5~163" class="Selection" value="163">Sri Lanka</option>
											<option id="QR~QID5~164" class="Selection" value="164">Sudan</option>
											<option id="QR~QID5~165" class="Selection" value="165">Suriname</option>
											<option id="QR~QID5~166" class="Selection" value="166">Swaziland</option>
											<option id="QR~QID5~167" class="Selection" value="167">Sweden</option>
											<option id="QR~QID5~168" class="Selection" value="168">Switzerland</option>
											<option id="QR~QID5~169" class="Selection" value="169">Syria</option>
											<option id="QR~QID5~170" class="Selection" value="170">Taiwan</option>
											<option id="QR~QID5~171" class="Selection" value="171">Tajikistan</option>
											<option id="QR~QID5~172" class="Selection" value="172">Tanzania</option>
											<option id="QR~QID5~173" class="Selection" value="173">Thailand</option>
											<option id="QR~QID5~174" class="Selection" value="174">Togo</option>
											<option id="QR~QID5~175" class="Selection" value="175">Tonga</option>
											<option id="QR~QID5~176" class="Selection" value="176">Trinidad and Tobago</option>
											<option id="QR~QID5~177" class="Selection" value="177">Tunisia</option>
											<option id="QR~QID5~178" class="Selection" value="178">Turkey</option>
											<option id="QR~QID5~179" class="Selection" value="179">Turkmenistan</option>
											<option id="QR~QID5~180" class="Selection" value="180">Tuvalu</option>
											<option id="QR~QID5~181" class="Selection" value="181">Uganda</option>
											<option id="QR~QID5~182" class="Selection" value="182">Ukraine</option>
											<option id="QR~QID5~183" class="Selection" value="183">United Arab Emirates</option>
											<option id="QR~QID5~184" class="Selection" value="184">United Kingdom</option>
											<option id="QR~QID5~185" class="Selection" value="185">United States</option>
											<option id="QR~QID5~186" class="Selection" value="186">Uruguay</option>
											<option id="QR~QID5~187" class="Selection" value="187">Uzbekistan</option>
											<option id="QR~QID5~188" class="Selection" value="188">Vanuatu</option>
											<option id="QR~QID5~189" class="Selection" value="189">Vatican City</option>
											<option id="QR~QID5~190" class="Selection" value="190">Venezuela</option>
											<option id="QR~QID5~191" class="Selection" value="191">Vietnam</option>
											<option id="QR~QID5~192" class="Selection" value="192">Yemen</option>
											<option id="QR~QID5~193" class="Selection" value="193">Zambia</option>
											<option id="QR~QID5~194" class="Selection" value="194">Zimbabwe</option>
											<option id="QR~QID5~195" class="Selection" value="195">Other</option>
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
								</div>
							</section>
						</div>

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