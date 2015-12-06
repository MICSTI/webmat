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
												$html .= "plus.style.display = 'inline-block';";
												$html .= "minus.style.display = 'none';";
											$html .= "} else {";
												$html .= "elem.style.display = 'block';";
												$html .= "plus.style.display = 'none';";
												$html .= "minus.style.display = 'inline-block';";
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