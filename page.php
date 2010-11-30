<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

<?php get_header(); ?>

		<div id="content" class="span-18" role="main">
			<div class="wrap">

				<?php
				/* Run the loop to output the posts.
				 * If you want to overload this in a child theme then include a file
				 * called loop-index.php and that will be used instead.
				 */
				 get_template_part( 'loop', 'page' );
				?>
				
			</div>
		</div><?php /* div#content */ ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>