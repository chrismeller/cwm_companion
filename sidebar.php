<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

<div id="sidebar" class="widget-area span-6 last" role="complementary">
	
	<?php
	
		if ( !dynamic_sidebar( 'primary-widget-area' ) ) {
			
			?>
			
				<div id="not-configured" class="widget-container">
					<div class="wrap">
						Configure some widgets!
					</div>
				</div>
			
			<?php
			
		}
	
	
	?>
	
</div>

<?php return; ?>
	
	<div id="archives" class="widget-container">
		<div class="wrap">
			<h3 class="widget-title">Archives</h3>
			<ul>
				<?php
					
					echo 'bar';
					
				?>
			</ul>
			<a class="more-archives" href="<?php echo home_url('/archives'); ?>" title="More archives...">More archives...</a>
		</div>
	</div>
</div>
<? return; ?>
	<ul class="xoxo">

		<?php
		
			/* When we call the dynamic_sidebar() function, it'll spit out
			 * the widgets for that widget area. If it instead returns false,
			 * then the sidebar simply doesn't exist, so we'll hard-code in
			 * some default sidebar stuff just in case.
			 */
			if ( ! dynamic_sidebar( 'primary-widget-area' ) ) {

				?>

					<li id="archives" class="widget-container">
						<h3 class="widget-title"><?php _e( 'Archives', 'twentyten' ); ?></h3>
						<ul>
							<?php wp_get_archives( 'type=monthly&show_post_count=true&limit=8' ); ?>
						</ul>
					</li>

					<li id="meta" class="widget-container">
						<h3 class="widget-title"><?php _e( 'Meta', 'twentyten' ); ?></h3>
						<ul>
							<?php wp_register(); ?>
							<li><?php wp_loginout(); ?></li>
							<?php wp_meta(); ?>
						</ul>
					</li>
	
				<?php
				
			}
			
		?>
			
	</ul>
</div>
