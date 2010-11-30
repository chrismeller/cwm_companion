<?php

	if ( !have_posts() ) {
		
		?>
		
			<div id="post-0" class="post error404 not-found last">
				<h1 class="entry-title"><?php _e( 'Not Found', 'twentyten' ); ?></h1>
				<div class="entry-content">
					<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten' ); ?></p>
					<?php get_search_form(); ?>
				</div><!-- .entry-content -->
			</div><!-- #post-0 -->
		
		<?php
		
	}
	
	$i = 1;
	
	while ( have_posts() ) {
		
		the_post();
		
		// blank out the class
		$class = array();
		
		// this approach lets us have a post with both first and last classes
		if ( $i == 1 ) {
			$class[] = 'first';
		}
		
		if ( $i == CWM::count_posts() ) {
			$class[] = 'last';
		}
		
		$class = implode( ' ', $class );
		
		?>
		
			<div id="post-<?php the_ID(); ?>" <?php post_class($class); ?>>
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	
				<div class="entry-meta">
					<?php twentyten_posted_on(); ?>
				</div><!-- .entry-meta -->
				
				<div class="entry-summary">
					<?php
						the_content();
					?>
				</div>
				
				<div class="entry-utility">
					
					<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'twentyten' ), 'after' => '</div>' ) ); ?>
					<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
					
				</div>
				
			</div><!-- #post-<?php the_ID(); ?> -->
		
		<?php

			$i++;
		
		
	}
	
?>