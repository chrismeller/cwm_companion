<?php

	class CWM_Companion extends Plugin {
		
		public function action_plugin_activation ( $file ) {
			
			CronTab::add_single_cron( 'cwm_flickr_updater-single', array( 'CWM_Companion', 'flickr_update' ), HabariDateTime::date_create() );
			CronTab::add_hourly_cron( 'cwm_flickr_updater', array( 'CWM_Companion', 'flickr_update' ) );
			
		}
		
		public function action_plugin_deactivation ( $file ) {
			
			CronTab::delete_cronjob( 'cwm_flickr_updater' );
			
		}
		
		public static function flickr_update ( ) {
			
			$feed = file_get_contents( 'http://api.flickr.com/services/feeds/photos_public.gne?id=27041953@N00&lang=en-us&format=rss_200' );
			
			if ( !$feed ) {
				EventLog::log( _t( 'Unable to fetch feed contents for Flickr items.', 'cwm' ) );
				return false;
			}
			
			$xml = new SimpleXMLElement( $feed );
			
			if ( count( $xml->channel->item ) < 1 ) {
				EventLog::log( _t( 'Invalid feed results for Flickr items.', 'cwm' ) );
				return false;
			}
			
			$items = array();
			
			foreach ( $xml->channel->item as $item ) {
				
				if ( !preg_match('/<img src="([^"]+)/si', $item->description, $matches) ) {
					continue;
				}
				
				// for reference: large => _m, proportional => _t, square => _s
				$suffix = '_s';
				
				$i = array(
					'title' => (string)$item->title,
					'link' => (string)$item->link,
					'thumbnail' => str_replace( '_m.jpg', $suffix . '.jpg', $matches[1] ),
					'image' => str_replace( '_m.jpg', '.jpg', $matches[1] ),
					'description' => (string)$item->description,
					'pubDate' => (string)$item->pubDate,
					'guid' => (string)$item->guid,
					'thumbnail_local' => '',
				);
				
				// if we don't already have this thumbnail cached
				if ( !Cache::has( 'cwm:flickr_thumbnail_' . md5( $i['guid'] ) ) ) {
					
					// snag the thumbnail and cache it locally
					$thumb = file_get_contents( $i['thumbnail'] );
					
					if ( $thumb !== false ) {
						
						// the thumbnail shouldn't change, so cache it for a long time - and keep it after expiration anyway
						Cache::set( 'cwm:flickr_thumbnail_' . md5( $i['guid'] ), $thumb, HabariDateTime::DAY * 7, true );
						
					}
					else {
						// we couldn't get this thumbnail, so skip this item for now
						continue;
					}
					
				}
				
				// set the local thumbnail element
				$i['thumbnail_local'] = URL::get( 'cwm_display_flickr_thumbnail', array( 'guid' => md5( $i['guid'] ) ) );
				
				// add the item to the list
				$items[] = $i;
				
			}
			
			// cache the output for 12 hours
			Cache::set( 'cwm:flickr_items', $items, HabariDateTime::HOUR * 12 );
			
			EventLog::log( _t( 'Flickr items updated', 'cwm' ) );
			
			// cron completed successfully
			return true;
			
		}
		
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
			
			$rule = new RewriteRule( array(
				'name' => 'cwm_display_flickr_thumbnail',
				'parse_regex' => '#^flickr_thumbnail/(?P<guid>\w+)?/?$#i',
				'build_str' => 'flickr_thumbnail/{$guid}',
				'handler' => 'UserThemeHandler',
				'action' => 'display_flickr_thumbnail',
				'rule_class' => RewriteRule::RULE_THEME,
				'is_active' => true,
				'description' => 'Display Flickr thumbnail from local cache',
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