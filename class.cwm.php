<?php

	class CWM {
		
		public function init ( ) {
			
			// don't load the main js script if it's the admin
			if ( is_admin() == false ) {
				wp_enqueue_script( 'chrismkii_main', get_bloginfo('template_directory') . '/js/main.js', array('jquery'), false, true );
			}

			if ( function_exists('wp_register_sidebar_widget') ) {
				wp_register_sidebar_widget( 'archives', 'Chris mk II: Archives', array( 'CWM', 'archives' ) );
				wp_register_sidebar_widget( 'flickr', 'Chris mk II: Flickr', array( 'CWM', 'flickr' ) );
			}
			
			//if ( is_single() ) {
				wp_enqueue_style( 'chrismkii_comments', get_bloginfo('template_directory') . '/comments.css', array(), false, 'all' );
			//}
			
		}
		
		public static function count_posts ( ) {
			
			global $wp_query;
			
			return $wp_query->post_count;
			
		}
		
		public static function archives ( $args = array() ) {
			
			echo $args['before_widget'];
			
			echo $args['before_title'] . 'Archives' . $args['after_title'];
			
			echo '<ul>';

			// delete any previously cached archives
			wp_cache_delete( 'wp_get_archives', 'general' );
			
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
			
			echo '</ul>';
			
			echo '<a href="' . home_url( '/archives' ) . '" class="more-archives" title="More archives...">More archives...</a>';
			
			echo $args['after_widget'];

		}
		
		public static function flickr ( $args = array() ) {
			
			$items = self::flickr_items();
			
			if ( !$items ) {
				return false;
			}
			
			echo $args['before_widget'];
			
			echo $args['before_title'] . 'Recent Photos' . $args['after_title'];
			
			echo '<ul>';
			
			foreach ( $items as $item ) {
				echo '<li><a href="' . esc_url( $item['link'] ) . '" title="' . esc_attr( $item['title'] ) . '"><img src="' . esc_url( $item['thumbnail'] ) . '" alt="' . esc_attr( $item['title'] ) . '" /></a>';
				echo '</li>';
			}
			
			echo '</ul>';
			
			echo '<a href="http://flickr.com/photos/mellertime" class="photostream">The rest of the photostream...</a>';
			
			echo $args['after_widget'];
			
		}
		
		public static function search ( ) {

			$s = get_search_query();

			if ( !$s ) {
				$s = 'Search';
			}

			return $s;

		}
		
		private function flickr_items ( ) {
			
			if ( $cache = wp_cache_get( 'flickr_items', 'chrismkii' ) ) {
				$items = $cache;
			}
			else {
				
				$feed = file_get_contents( 'http://api.flickr.com/services/feeds/photos_public.gne?id=27041953@N00&lang=en-us&format=rss_200' );
				
				if ( !$feed ) {
					return false;
				}
				
				$xml = new SimpleXMLElement( $feed );
				
				if ( count( $xml->channel->item ) < 1 ) {
					return false;
				}
				
				$items = array();
				
				foreach ( $xml->channel->item as $item ) {
					
					if ( !preg_match('/<img src="([^"]+)/si', $item->description, $matches) ) {
						continue;
					}
					
					// for reference: large => _m, proportional => _t, square => _s
					$suffix = '_s';
					
					$items[] = array(
						'title' => $item->title,
						'link' => $item->link,
						'thumbnail' => str_replace( '_m.jpg', $suffix . '.jpg', $matches[1] ),
						'image' => str_replace( '_m.jpg', '.jpg', $matches[1] ),
						'description' => $item->description,
						'pubDate' => $item->pubDate,
						'guid' => $item->guid,
					);
				}
				
				// cache the output - 43200 == 12 hours
				wp_cache_add( 'flickr_items', $items, 'chrismkii', 43200 );
				
			}
			
			// shuffle the array
			shuffle( $items );
			
			$return = array();
			
			// return 3 random objects
			for ( $i = 0; $i < 3; $i++ ) {
				// pop an item off the end of the array
				$return[] = array_pop( $items );
			}
			
			return $return;
			
		}
		
	}
	
	add_action( 'wp_loaded', array( 'CWM', 'init' ) );

?>
