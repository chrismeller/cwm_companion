<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>
		
	</div><?php /* div#main */ ?>

	<div id="footer" class="span-24 last" role="contentinfo">
		<div id="colophon">
		
			<div id="copyright" class="span-15">
				<p>Presented by Chris Meller. Licensed under a <a rel="license" href="http://creativecommons.org/licenses/by/3.0/us/" title="Creative Commons Attribution 3.0 United States License">Creative Commons</a> license.</p>
			</div>

		</div><!-- #colophon -->
	</div><!-- #footer -->

</div><?php /* div#page */ ?>

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>

<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
</body>
</html>
