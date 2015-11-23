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
					
						<div class="webmat-title">The Tool</div>
						
						<div class="question">Please tick the boxes below and tick all boxes that are appropriate for your study.</div>
						
						<div class="page-info">It is possible that there are no results shown for your selection. That is because the measurements included now have been systematically selected.</div>
						
						<div class="the-tool-container">
							
							<div class="the-tool-progress">
								<span class="the-tool-circle the-tool-category-selected" id="category-age" data-category="age" data-no="1"></span>
								<span class="the-tool-line"></span>
								<span class="the-tool-circle" id="category-field" data-category="field" data-no="2"></span>
								<span class="the-tool-line"></span>
								<span class="the-tool-circle" id="category-workplace" data-category="workplace" data-no="3"></span>
								<span class="the-tool-line"></span>
								<span class="the-tool-circle" id="category-size" data-category="size" data-no="4"></span>
							</div>
							
							<div class="the-tool-section">
								<div class="the-tool-content the-tool-content-active" id="content-age">
									<div class="category-question">On which group are you focusing in your study?</div>
									
									<div class="category-choice-container">
										
										<div class="category-choice">Children</div>
										<div class="category-choice">Adolescents</div>
										<div class="category-choice">Adults</div>
										<div class="category-choice">Elderly</div>
										
									</div>
								</div>
								
								<div class="the-tool-content" id="content-field">
									<div class="category-question">What do you want to study?</div>
									
									<div class="category-choice-container">
									
										<div class="category-row">
											<div class="category-choice">General well-being</div>
											<div class="category-choice">Feelings</div>
											<div class="category-choice">Life satisfaction</div>
											<div class="category-choice">Flourishing</div>
										</div>
										
										<div class="category-row">
											<div class="category-choice">Resilience</div>
											<div class="category-choice">Mindfulness</div>
											<div class="category-choice">Self-esteem/Self-efficacy</div>
										</div>
										
										<div class="category-row">
											<div class="category-choice">Optimism</div>
											<div class="category-choice">Meaning and purpose</div>
											<div class="category-choice">Engagement</div>
										</div>
										
										<div class="category-row">
											<div class="category-choice">Autonomy</div>
											<div class="category-choice">Commitment</div>
											<div class="category-choice">Competence</div>
										</div>
									
									</div>
								</div>
								
								<div class="the-tool-content" id="content-workplace">
									<div class="category-question">Is it a workplace setting?</div>
									
									<div class="category-choice-container">
									
										<div class="category-choice">Yes</div>
										<div class="category-choice">No</div>
									
									</div>
								</div>
								
								<div class="the-tool-content" id="content-size">
									<div class="category-question">How long should the test be?</div>
									
									<div class="category-choice-container">
									
										<div class="category-row">
											<div class="category-choice">Single-item</div>
											<div class="category-choice">General indicators</div>
											<div class="category-choice">2-10 items</div>
										</div>
										
										<div class="category-row">
											<div class="category-choice">11-20 items</div>
											<div class="category-choice">21-30 items</div>
											<div class="category-choice">more than 30 items</div>
										</div>
									
									</div>
								</div>
							</div>
							
							<div class="the-tool-next">
								<button class="the-tool-button" id="the-tool-button-back">Step back</button> 
								<button class="the-tool-button" id="the-tool-button-next">Next step</button> 
								<button class="the-tool-button" id="the-tool-button-submit">Show me the results</button> 
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