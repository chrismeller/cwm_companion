<?php

	class CWM_Companion extends Plugin {
		
		public function action_plugin_activation ( $file ) {
			
			CronTab::add_single_cron( 'cwm_flickr_updater-single', array( 'CWM_Companion', 'flickr_update' ), HabariDateTime::date_create() );
			CronTab::add_hourly_cron( 'cwm_flickr_updater', array( 'CWM_Companion', 'flickr_update' ) );
			
			CronTab::add_single_cron( 'cwm_commit_stats-single', array( 'CWM_Companion', 'update_commit_stats' ), HabariDateTime::date_create() );
			CronTab::add_hourly_cron( 'cwm_commit_stats', array( 'CWM_Companion', 'update_commit_stats' ) );
			
		}
		
		public function action_plugin_deactivation ( $file ) {
			
			CronTab::delete_cronjob( 'cwm_flickr_updater' );
			CronTab::delete_cronjob( 'cwm_flickr_update-single' );
			
			CronTab::delete_cronjob( 'cwm_commit_stats' );
			CronTab::delete_cronjob( 'cwm_commit_stats-single' );
			
		}
		
		public function filter_plugin_config ( $actions, $plugin_id ) {
			
			if ( $plugin_id == $this->plugin_id() ) {
				
				$actions[] = _t('Update Flickr Cache');
				$actions[] = _t('Configure');
				
			}
			
			return $actions;
			
		}
		
		public function action_plugin_ui ( $plugin_id, $action ) {
			
			if ( $plugin_id == $this->plugin_id() ) {
			
				switch ( $action ) {
					
					case _t('Update Flickr Cache'):
						
						self::flickr_update();
						
						Session::notice( _t('Flickr items updated!') );
						Utils::redirect( URL::get( 'admin', 'page=plugins' ) );
						
						break;
						
					case _t('Configure'):
						
						$ui = new FormUI( 'cwm_companion' );
						$ui->append( 'text', 'cwm_companion__cloudfiles_user', 'option:cwm_companion__cloudfiles_user', _t('Cloud Files Username'));
						$ui->append( 'text', 'cwm_companion__cloudfiles_api_key', 'option:cwm_companion__cloudfiles_api_key', _t('Cloud Files API Key'));
						$ui->append( 'text', 'cwm_companion__cloudfiles_container', 'option:cwm_companion__cloudfiles_container', _t('Cloud Files Container'));
						
						$ui->append( 'submit', 'save', _t( 'Save' ) );
						
						$ui->out();
						
						break;
					
				}
				
			}
			
		}
		
		public static function flickr_update ( ) {
			
			// if we've configured cloudfiles
			if ( Options::get( 'cwm_companion__cloudfiles_user' ) != null ) {
				$cloud_files = true;
				
				// include the cloudfiles API
				include('vendor/php-cloudfiles/cloudfiles.php');
			}
			else {
				$cloud_files = false;
			}
			
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
			
			if ( $cloud_files ) {
				// now that we've got feed contents, take the time to authenticate to cloudfiles
				$auth = new CF_Authentication( Options::get( 'cwm_companion__cloudfiles_user' ), Options::get( 'cwm_companion__cloudfiles_api_key' ) );
				$auth->authenticate();
				
				EventLog::log( _t('Successfully authenticated to Cloud Files'), 'info' );
				
				// and initialize the connection
				$conn = new CF_Connection( $auth );
				
				// and get the container
				$container = $conn->get_container( Options::get( 'cwm_companion__cloudfiles_container' ) );
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
					'guid' => md5( (string)$item->guid ),
					'thumbnail_local' => '',
				);
				
				// if we're using cloudfiles, caching is different
				if ( $cloud_files ) {
					
					// first see if we already have this one cached
					try {
						$object = $container->get_object( $i['guid'] . '.jpg' );
					}
					catch ( NoSuchObjectException $e ) {
						
						// we don't have it, we need to store it
						
						// snag it
						$thumb = file_get_contents( $i['thumbnail'] );
						
						// create the new object
						$object = $container->create_object( $i['guid'] . '.jpg' );
						
						// put it
						$object->write( $thumb );
						
					}
					
					// now either way we should have an object
					$i['thumbnail_local'] = $object->public_ssl_uri();
					
				}
				else {
					
					// otherwise, fall back to local caching so nothing breaks
					
					// if we don't already have this thumbnail cached
					if ( !Cache::has( 'cwm:flickr_thumbnail_' . $i['guid'] ) ) {
						
						// snag the thumbnail and cache it locally
						$thumb = file_get_contents( $i['thumbnail'] );
						
						if ( $thumb !== false ) {
							
							// the thumbnail shouldn't change, so cache it for a long time
							Cache::set( 'cwm:flickr_thumbnail_' . $i['guid'], $thumb, HabariDateTime::MONTH );
							
						}
						else {
							// we couldn't get this thumbnail, so skip this item for now
							continue;
						}
						
					}
				
					// set the local thumbnail element
					$i['thumbnail_local'] = URL::get( 'cwm_display_flickr_thumbnail', array( 'guid' => $i['guid'] ) );
					
				}
					
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
				'parse_regex' => '#^flickr_thumbnail/(?P<guid>\w+).jpg$#i',
				'build_str' => 'flickr_thumbnail/{$guid}.jpg',
				'handler' => 'UserThemeHandler',
				'action' => 'display_flickr_thumbnail',
				'rule_class' => RewriteRule::RULE_THEME,
				'is_active' => true,
				'description' => 'Display Flickr thumbnail from local cache',
			) );
			
			$rules[] = $rule;
			
			$rule = new RewriteRule( array(
				'name' => 'cwm_display_header',
				'parse_regex' => '#^header.json?/?$#i',
				'build_str' => 'header.json',
				'handler' => 'UserThemeHandler',
				'action' => 'display_header',
				'rule_class' => RewriteRule::RULE_THEME,
				'is_active' => true,
				'description' => 'Display header image',
			) );
			
			$rules[] = $rule;
			
			return $rules;
			
		}
		
		public function action_post_insert_after ( $post ) {
			
			$this->expire_archives();
			
		}
		
		public function action_post_update_status ( $post, $old_value = null, $new_value = null ) {
			
			$this->expire_archives();
			
		}
		
		private function expire_archives ( ) {
			
			// the archives pages
			Cache::expire( 'cwm:archives:*', 'glob' );
			
			// the archives block
			Cache::expire( 'cwm:archives_block' );
			
		}
		
		public function update_commit_stats ( ) {
			
			try {
				$last_52 = RemoteRequest::get_contents( 'http://tools.chrismeller.com/commitstats/stats_last_52' );
				$last_52 = json_decode( $last_52 );
				
				$this_week = RemoteRequest::get_contents( 'http://tools.chrismeller.com/commitstats/stats_this_week' );
				$this_week = json_decode( $this_week );
				
				$total_this_week = RemoteRequest::get_contents( 'http://tools.chrismeller.com/commitstats/total_this_week' );
				$total_this_week = json_decode( $total_this_week );
				
				// save the cache for 12 hours, just to make sure we don't run out of stats if we can't update one cron
				Cache::set( 'cwm:commit_stats:stats_last_52', $last_52, HabariDateTime::HOUR * 12 );
				Cache::set( 'cwm:commit_stats:stats_this_week', $this_week, HabariDateTime::HOUR * 12 );
				Cache::set( 'cwm:commit_stats:stats_total_this_week', $total_this_week, HabariDateTime::HOUR * 12 );
				
				Cache::set( 'cwm:commit_stats:last_update', HabariDateTime::date_create()->int, HabariDateTime::HOUR * 12 );
			}
			catch ( RemoteRequest_Timeout $e ) {
				// nothing, we just won't update this run
			}
			
		}
		
	}
	
?>