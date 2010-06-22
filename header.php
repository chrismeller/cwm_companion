<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title>
	<?php // Returns the title based on what is being viewed
		if ( is_single() ) { // single posts
			single_post_title(); echo ' | '; bloginfo( 'name' );
		// The home page or, if using a static front page, the blog posts page.
		} elseif ( is_home() || is_front_page() ) {
			bloginfo( 'name' );
			if( get_bloginfo( 'description' ) )
				echo ' | ' ; bloginfo( 'description' );
			twentyten_the_page_number();
		} elseif ( is_page() ) { // WordPress Pages
			single_post_title( '' ); echo ' | '; bloginfo( 'name' );
		} elseif ( is_search() ) { // Search results
			printf( __( 'Search results for %s', 'twentyten' ), '"'.get_search_query().'"' ); twentyten_the_page_number(); echo ' | '; bloginfo( 'name' );
		} elseif ( is_404() ) {  // 404 (Not Found)
			_e( 'Not Found', 'twentyten' ); echo ' | '; bloginfo( 'name' );
		} else { // Otherwise:
			wp_title( '' ); echo ' | '; bloginfo( 'name' ); twentyten_the_page_number();
		}
	?>
	</title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	
	<link rel="stylesheet" type="text/css" media="screen, projection" href="<?php bloginfo( 'template_directory' ); ?>/blueprint/screen.css" />
	<link rel="stylesheet" type="text/css" media="print" href="<?php bloginfo( 'template_directory' ); ?>/blueprint/print.css" />
	
	<!-- [if lt IE 8]>
		<link rel="stylesheet" type="text/css" media="screen, projection" href="<?php bloginfo( 'template_directory' ); ?>/blueprint/ie.css" />
	<![endif]-->
	
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */

	wp_head();
?>
</head>

<body <?php body_class(); ?>>

	<div id="page" class="hfeed container">
	
		<div id="header" class="span-24 last" role="banner">
		
			<div id="logo" class="span-8">
				
				<h1 id="site-title">
					<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
						<span>
							<?php bloginfo( 'name' ); ?>
						</span>
					</a>
				</h1>
				
			</div>
			
			<div id="menu" class="prepend-2 span-8" role="navigation">
				<?php 
					/* Our navigation menu. If one isn't filled out, wp_nav_menu falls back to wp_page_menu. The menu assigned to the primary position is the one used. If none is assigned, the menu with the lowest ID is used. */
					wp_nav_menu( array( 'sort_column' => 'menu_order', 'container_class' => 'menu-header', 'theme_location' => 'primary' ) );
				?>
			</div> <?php /* div#menu */ ?>
			
			<div id="search" class="prepend-1 span-5 last">
				<input type="search" placeholder="Search" />
			</div>
			
		</div> <?php /* div#header */ ?>
		
		<div id="banner" class="span-24 last">
			<img src="<?php header_image(); ?>" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" alt="" />
		</div>
		
		<div id="main" class="span-24 last">
