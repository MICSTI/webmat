<?php
/**
 * Results page
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>

	<?php
		if ($_POST) {
			// save survey details
			$request_id = isset($_POST["request-id"]) ? $_POST["request-id"] : null;
			
			if ($request_id != null) {
				try {
					$db = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASSWORD);
					$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
					
					// was the survey filled out on the feedback page?
					if ($request_id == "_feedback") {
						// save request to database
						$ip = $_SERVER['REMOTE_ADDR'];
						
						// request meta data
						$request_id = addRequestMetaData($db, $ip);
					} 
					
					// add survey details to db
					addSurveyDetails($db, $request_id, $_POST, $FIELDS_SURVEY, $KEY_PROPERTY);
					
					// set survey flag in the_tool_data
					setSurveyFlag($db, $request_id, 1);
					
					// add mail address also if it was set
					$mail = isset($_POST["mail"]) ? trim($_POST["mail"]) : null;
					
					if ($mail != null) {
						addRequestMail($db, $request_id, $mail);
					}
				} catch(PDOException $ex) {
					echo "An error occurred during the database query!\n";
					echo $ex;
				}
			}
		}
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
						
						<div class="survey-complete">
							Thanks for completing the survey!
						</div>
						
						<div class="survey-back">
							<a href="/">Back to home</a>
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