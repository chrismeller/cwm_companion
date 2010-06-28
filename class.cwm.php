<?php

	class CWM {
		
		public function init ( ) {
			
			wp_enqueue_script( 'chrismkii_main', get_bloginfo('template_directory') . '/js/main.js', array('jquery'), false, true );

			if ( function_exists('wp_register_sidebar_widget') ) {
				wp_register_sidebar_widget( 'chrismkii_archives', 'Chris mk II Archives', array( 'CWM', 'archives_widget' ) );
			}
			
		}
		
		public function archives_widget ( $args ) {
			
			
			
		}
		
		public function archives ( ) {

			// cause wordpress to cache the raw array so we can snag it
			wp_get_archives( 'type=monthly&show_post_count=true&limit=8&echo=0' );

			$archives = wp_cache_get( 'wp_get_archives' , 'general');

			// we don't care what the md5 hash is, just get the first element - that holds our values
			$keys = array_keys( $archives );
			$archives = $archives[ $keys[0] ];

			$i = 1;
			foreach ( $archives as $archive ) {

				$url = get_month_link( $archive->year, $archive->month );

				if ( $i == 1 ) {
					$class = 'class="first"';
				}
				else if ( $i == count( $archives ) ) {
					$class = 'class="last"';
				}
				else {
					$class = '';
				}

				$month = mktime( 0, 0, 0, $archive->month );
				$month = date('F', $month);

				$text = $month . ', ' . $archive->year;

				// get_archives_link() won't let you add a class to the LI element... god dammit!
				echo '<li ' . $class . '><a href="' . esc_url( $url ) . '" title="' . esc_attr( $text ) . '">' . $text . '</a><span class="post-count">' . $archive->posts . '</span></li>';

				$i++;

			}

		}
		
		public static function search ( ) {

			$s = get_search_query();

			if ( !$s ) {
				$s = 'Search';
			}

			return $s;

		}
		
	}
	
	add_action( 'init', array( 'CWM', 'init' ) );

?>