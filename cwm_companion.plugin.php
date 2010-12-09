<?php

	class CWM_Companion extends Plugin {
		
		public function filter_rewrite_rules ( $rules ) {
			
			$rule = new RewriteRule( array(
				'name' => 'cwm_display_archives',
				'parse_regex' => '#^archives(?:/page/(?P<page>\d+))?/?$#i',
				'build_str' => 'archives(/page/{$page})',
				'handler' => 'UserThemeHandler',
				'action' => 'display_archives',
				'rule_class' => RewriteRule::RULE_THEME,
				'is_active' => true,
				'description' => 'Display custom archives'
			) );
			
			$rules[] = $rule;
			
			return $rules;
			
		}
		
		public function filter_theme_act_display_archives ( $handled, $theme ) {
			
			$page = Controller::get_var( 'page', 1 );
			
			$cache_name = 'cwm:archives_page_' . $page;
			
			if ( Cache::has( $cache_name ) ) {
				$theme->posts = Cache::get( $cache_name );
			}
			else {
				
				// get the posts
				$posts = Posts::get( array( 'content_type' => Post::type( 'entry' ), 'status' => Post::status( 'published' ), 'limit' => 25, 'page' => $page ) );
				
				Cache::set( $cache_name, $posts, HabariDateTime::HOUR * 12 );
				
				$theme->posts = $posts;
				
			}
			
			//echo $theme->display_fallback( array( 'page.archives', 'page' ) );
			
			return false;
			
		}
		
	}
	
?>