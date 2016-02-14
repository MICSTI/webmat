<?php
/**
 * Results page
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

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

					<div class="entry-content the-tool-home">
					
						<div class="webmat-intro">
							<div class="category-question">Welcome to WEBMAT</div>
							
							<div class="the-tool-intro-text">
								WEBMAT is an interactive tool for advising on the best available well-being measurements for users’ specific needs, taking into account study design, purpose, application and length.
							</div>
						</div>
						
						<div class="the-tool-intro">
							<div class="category-question">Use The Tool</div>
							
							<div class="the-tool-intro-text">
								<div>
									There are some questions following which are about different aspects regarding your study. You can select as many boxes as you like at each question. It is possible that there are no results shown for your selection. That is because the measurements included now have been systematically selected. In that case you can try to choose fewer boxes at one question or to take a broader approach for example with not ticking any box for the last question.
								</div>
								
								<div>
									Please keep in mind that "The Tool" is now still a beta version and that there are still improvements made.
								</div>
							</div>
							
							<div>
								<button class="webmat-button" id="the-tool-button-start" onclick="showTheTool()">Let's start</button>
							</div>
						</div>
						
						<div class="the-tool-wrapper">
							<!--
							<div class="question">Please tick the boxes below and tick all boxes that are appropriate for your study.</div>
							
							<div class="page-info">It is possible that there are no results shown for your selection. That is because the measurements included now have been systematically selected.</div>-->
							
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
											
											<div class="category-choice" data-id="children">Children</div>
											<div class="category-choice" data-id="adolescents">Adolescents</div>
											<div class="category-choice" data-id="adults">Adults</div>
											<div class="category-choice" data-id="elderly">Elderly</div>
											
										</div>
									</div>
									
									<div class="the-tool-content" id="content-field">
										<div class="category-question">What do you want to study?</div>
										
										<div class="category-choice-container">
										
											<div class="category-row">
												<div class="category-choice" data-id="general">General well-being</div>
												<div class="category-choice" data-id="feeling">Feelings</div>
												<div class="category-choice" data-id="life-satisfaction">Life satisfaction</div>
												<div class="category-choice" data-id="flourishing">Flourishing</div>
											</div>
											
											<div class="category-row">
												<div class="category-choice" data-id="resilience">Resilience</div>
												<div class="category-choice" data-id="mindfulness">Mindfulness</div>
												<div class="category-choice" data-id="self-esteem-efficacy">Self-esteem/Self-efficacy</div>
											</div>
											
											<div class="category-row">
												<div class="category-choice" data-id="optimism">Optimism</div>
												<div class="category-choice" data-id="meaning-purpose">Meaning and purpose</div>
												<div class="category-choice" data-id="engagement">Engagement</div>
											</div>
											
											<div class="category-row">
												<div class="category-choice" data-id="autonomy">Autonomy</div>
												<div class="category-choice" data-id="commitment">Commitment</div>
												<div class="category-choice" data-id="competence">Competence</div>
											</div>
										
										</div>
									</div>
									
									<div class="the-tool-content" id="content-workplace">
										<div class="category-question">Is it a workplace setting?</div>
										
										<div class="category-choice-container">
										
											<div class="category-choice" data-id="workplace">Yes</div>
											<div class="category-choice">No</div>
										
										</div>
									</div>
									
									<div class="the-tool-content" id="content-size">
										<div class="category-question">How long should the test be?</div>
										
										<div class="category-choice-container">
										
											<div class="category-row">
												<div class="category-choice" data-id="items-single">Single-item</div>
												<div class="category-choice" data-id="general-indicators">General indicators</div>
												<div class="category-choice" data-id="items-2-10">2-10 items</div>
											</div>
											
											<div class="category-row">
												<div class="category-choice" data-id="items-11-20">11-20 items</div>
												<div class="category-choice" data-id="items-21-30">21-30 items</div>
												<div class="category-choice" data-id="items-30-+">more than 30 items</div>
											</div>
										
										</div>
									</div>
								</div>
								
								<div class="the-tool-next">
									<div><button class="webmat-button" id="the-tool-button-back">Step back</button></div>
									<div><button class="webmat-button" id="the-tool-button-next">Next step</button></div>
									<div><button class="webmat-button" id="the-tool-button-submit">Show me the results</button></div>
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