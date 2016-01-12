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

	<?php
		try {
			$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASSWORD);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		} catch(PDOException $ex) {
			echo "An error occurred during the database query!\n";
			echo $ex;
		}
		
		$requests_number = getNoToolRequests($db);
		$surveys_number = getNoSurveyFilled($db);
		
		$os_data = getOSStats($db);
		$browser_data = getBrowserStats($db);
		
		$os_colors = $COLORS;
		shuffle($os_colors);
		
		$browser_colors = $COLORS;
		shuffle($browser_colors);
	?>

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
						
						<div class="webmat-title">Stats</div>
						
						<div class="stats-container">
							<div class="stats-nav">
								<a class="stats-nav-elem stats-nav-elem-active" data-tab="general" data-default="true">General</a>
								<a class="stats-nav-elem" data-tab="tool">The Tool</a>
								<a class="stats-nav-elem" data-tab="survey">Survey</a>
							</div>
							
							<div class="stats-content">
								<div class="stats-tab-content stats-tab-content-active" id="stats-content-general">
									<div class="stats-info-elem-wrapper">
										<span class="stats-info-elem">
											<span class="stats-info-property"># of <span class="stats-info-highlight">The Tool</span> requests</span>
											<span class="stats-info-value"><?php echo $requests_number; ?></span>
										</span>
									</div>
									
									<div class="stats-info-elem-wrapper">
										<span class="stats-info-elem">
											<span class="stats-info-property"># of surveys filled out</span>
											<span class="stats-info-value">
												<?php
													$percent = $requests_number > 0 ? " (" . round($surveys_number / $requests_number * 100, 0) . "%)" : "";
													echo $surveys_number . $percent;
												?>
											</span>
										</span>
									</div>
									
									<div class="stats-info-group-wrapper">
										<span class="stats-info-group">
											<div class="stats-info-group-title">Operating systems used</div>
											<div class="stats-info-group-content">
												<div class="stats-info-group-detail">
													<?php
														echo displayChartDetails($os_data, "OperatingSystem", "OSCount", $os_colors);
													?>
												</div>
												
												<div class="stats-info-group-chart">
													<canvas id="chart-os" width="240" height="240" />
												</div>
											</div>
										</span>
										
										<script type="text/javascript">
											<?php
												echo "var osData = " . transformChartData($os_data, "OperatingSystem", "OSCount", $os_colors) . ";";
											?>
										</script>
									</div>
									
									<div class="stats-info-group-wrapper">
										<span class="stats-info-group">
											<div class="stats-info-group-title">Browsers used</div>
											<div class="stats-info-group-content">
												<div class="stats-info-group-detail">
													<?php
														echo displayChartDetails($browser_data, "Browser", "BrowserCount", $browser_colors);
													?>
												</div>
												
												<div class="stats-info-group-chart">
													<canvas id="chart-browser" width="240" height="240" />
												</div>
											</div>
										</span>
										
										<script type="text/javascript">
											<?php
												echo "var browserData = " . transformChartData($browser_data, "Browser", "BrowserCount", $browser_colors) . ";";
											?>
										</script>
									</div>
								</div>
								
								<div class="stats-tab-content" id="stats-content-tool">
									<?php
										// iterate over tool fields
										$categories = array_keys($FIELDS_TOOL);
										
										foreach ($categories as $category) {
											$elements = $FIELDS_TOOL[$category];
											
											echo "<div class='stats-info-group-wrapper'>";
												echo "<span class='stats-info-group'>";
													// group title
													echo "<div class='stats-info-group-title'>" . $category . "</div>";
												
													// group content
													echo "<div class='stats-info-group-content'>";
														foreach ($elements as $element) {
															$property_sum = getPropertySum($db, $element[$KEY_PROPERTY]);
															
															$percent = round($property_sum / $requests_number * 100, 0);
															
															echo "<div class='stats-info-key-bar'>";
																echo "<span class='stats-info-key-fixed-200'>" . $element[$KEY_DISPLAY] . "</span>";
																echo "<span class='stats-info-key-progress'>";
																	echo "<span class='stats-info-key-progress-value' data-value='" . $percent . "' data-real='" . $property_sum . " out of " . $requests_number . "'>" . $percent . "%</span>";
																	echo "<span class='stats-info-key-progress-filled' data-value='" . $percent . "' ></span>";
																echo "</span>";
															echo "</div>";
														}
													echo "</div>";
												echo "</span>";
											echo "</div>";
										}
									?>
								</div>
								
								<div class="stats-tab-content" id="stats-content-survey">
									<?php
										// iterate over survey fields
										$questions = $FIELDS_SURVEY["Questions"];
										
										foreach ($questions as $question) {
											echo "<div class='stats-info-group-wrapper'>";
												$width = $question[$KEY_TYPE] == "doughnut" ? "width: 260px !important;" : "";
											
												echo "<span class='stats-info-group' style='" . $width . "'>";
													// group title
													echo "<div class='stats-info-group-title'>" . $question[$KEY_DISPLAY] . "</div>";
													
													// group content
													echo "<div class='stats-info-group-content'>";
														switch($question[$KEY_TYPE]) {
															case "doughnut":
																$stats_data = getSurveyStats($db, $question[$KEY_PROPERTY]);
																$stats_colors = $COLORS;
																shuffle($stats_colors);
																
																if (count($stats_data) > 0) {
																	$var_name = "stats_" . $question[$KEY_PROPERTY];
																	
																	$translation = array_key_exists($KEY_TRANSLATION, $question) ? $question[$KEY_TRANSLATION] : array();
																	
																	echo "<div class='stats-info-group-detail'>";
																		echo displayChartDetails($stats_data, "ValueName", "ValueCount", $stats_colors, $translation);
																	echo "</div>";
																	
																	echo "<div class='stats-info-group-chart'>";
																		echo "<canvas id='chart-" . $question[$KEY_PROPERTY] . "' width='150' height='150' />";
																	echo "</div>";
																	
																	echo "<script type='text/javascript'>";
																		echo "var " . $var_name . " = " . transformChartData($stats_data, "ValueName", "ValueCount", $stats_colors, $question[$KEY_TRANSLATION]) . ";";
																	echo "</script>";
																} else {
																	echo "Unfortunately, no data to show yet.";
																}
																
																break;
																
															case "bar-horizontal":
																$stats_data = getSurveyStats($db, $question[$KEY_PROPERTY]);
																
																if (count($stats_data > 0)) {
																	$count = 0;
																	foreach($stats_data as $tupel) {
																		$count += $tupel["ValueCount"];
																	}
																	
																	foreach($stats_data as $tupel) {
																		$percent = round($tupel["ValueCount"] / $count * 100, 0);
																		
																		$translation = array_key_exists($KEY_TRANSLATION, $question) ? $question[$KEY_TRANSLATION] : array();
																		
																		if (array_key_exists($tupel["ValueName"], $translation)) {
																			$display = $translation[$tupel["ValueName"]];
																		} else {
																			$display = $tupel["ValueName"];
																		}
																		
																		echo "<div class='stats-info-key-bar'>";
																			echo "<span class='stats-info-key-fixed-200'>" . $display . "</span>";
																			echo "<span class='stats-info-key-progress'>";
																				echo "<span class='stats-info-key-progress-value' data-value='" . $percent . "' data-real='" . $tupel["ValueCount"] . " out of " . $count . "'>" . $percent . "%</span>";
																				echo "<span class='stats-info-key-progress-filled' data-value='" . $percent . "' ></span>";
																			echo "</span>";
																		echo "</div>";
																	}
																} else {
																	echo "Unfortunately, no data to show yet.";
																}
																
																break;
																
															case "list-plain":
																$stats_data = getSurveyStatsPlainList($db, $question[$KEY_PROPERTY]);
																
																if (count($stats_data > 0)) {
																	echo displayPlainList($stats_data);
																} else {
																	echo "Unfortunately, no data to show yet.";
																}
																
																break;
																
															default:
																break;
														}
													echo "</div>";
												echo "</span>";
											echo "</div>";
										}
									?>
								</div>
							</div>
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