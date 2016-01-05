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
														echo displayChartDetails($os_data, "OperatingSystem", "OSCount", $COLORS);
													?>
												</div>
												
												<div class="stats-info-group-chart">
													<canvas id="chart-os" width="240" height="240" />
												</div>
											</div>
										</span>
										
										<script type="text/javascript">
											<?php
												echo "var osData = " . transformChartData($os_data, "OperatingSystem", "OSCount", $COLORS) . ";";
											?>
										</script>
									</div>
									
									<div class="stats-info-group-wrapper">
										<span class="stats-info-group">
											<div class="stats-info-group-title">Browsers used</div>
											<div class="stats-info-group-content">
												<div class="stats-info-group-detail">
													<?php
														echo displayChartDetails($browser_data, "Browser", "BrowserCount", $COLORS);
													?>
												</div>
												
												<div class="stats-info-group-chart">
													<canvas id="chart-browser" width="240" height="240" />
												</div>
											</div>
										</span>
										
										<script type="text/javascript">
											<?php
												echo "var browserData = " . transformChartData($browser_data, "Browser", "BrowserCount", $COLORS) . ";";
											?>
										</script>
									</div>
								</div>
								
								<div class="stats-tab-content" id="stats-content-tool">
									The Tool
								</div>
								
								<div class="stats-tab-content" id="stats-content-survey">
									Survey
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