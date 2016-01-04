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
						
						<div class="webmat-title">Stats</div>
						
						<div class="stats-container">
							<div class="stats-nav">
								<a class="stats-nav-elem stats-nav-elem-active" data-tab="general">General</a>
								<a class="stats-nav-elem" data-tab="tool">The Tool</a>
								<a class="stats-nav-elem" data-tab="survey">Survey</a>
							</div>
							
							<div class="stats-content">
								<div class="stats-tab-content stats-tab-content-active" id="stats-content-general">
									General
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