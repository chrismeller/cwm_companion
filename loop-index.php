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
						the_excerpt( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten' ) );
					?>
				</div>
				
				<div class="entry-utility">
					
					<?php
					
						if ( count( get_the_category() ) ) {

							?>
							
								<span class="cat-links">
									<span class="entry-utility-prep entry-utility-prep-cat-links"><?php printf( __('Posted in %s', 'twentyten' ), '</span> ' . get_the_category_list( ', ' ) ); ?>
								</span>
								<span class="meta-sep">|</span>
							
							<?php
							
						}
						
						$tags_list = get_the_tag_list( '', ', ' );
						
						if ( $tags_list ) {

							?>
							
								<span class="tag-links">
									<span class="entry-utility-prep entry-utility-prep-tag-links"><?php printf( __('Tagged %s', 'twentyten'), '</span> ' . $tags_list ); ?>
								</span>
								<span class="meta-sep">|</span>
							
							<?php
							
						}
					
					?>
					
					<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'twentyten' ), __( '1 Comment', 'twentyten' ), __( '% Comments', 'twentyten' ) ); ?></span>
					<?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
					
				</div>
				
			</div><!-- #post-<?php the_ID(); ?> -->
		
		<?php
		
			comments_template( '', true );
			
			
			
			$i++;
		
		
	}
	
	if ( function_exists('wp_pagenavi') ) {
		wp_pagenavi();
	}
	else {
		?>
			<?php if (  $wp_query->max_num_pages > 1 ) : ?>
							<div id="nav-below" class="navigation">
								<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'twentyten' ) ); ?></div>
								<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
							</div><!-- #nav-below -->
			<?php endif; ?>
		<?php
	}

?>