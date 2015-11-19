<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Expound
 */
?>

	</div><!-- #main -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<div class="footer-cambridge">
				<span class="cam-text">In cooperation with the PRG team, University of Cambridge</span>
				<span class="cam-img">
					<img src="http://webmat.micsti.at/wp-content/uploads/2015/11/logo-cam.jpg" width="152" height="40" />
				</span>
			</div>
			<?php do_action('expound_credits'); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>