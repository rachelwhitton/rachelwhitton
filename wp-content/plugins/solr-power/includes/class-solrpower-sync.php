<?php

class SolrPower_Sync {

	/**
	 * Singleton instance
	 * @var SolrPower_Sync|Bool
	 */
	private static $instance = false;

	/**
	 * Last error message.
	 * @var string
	 */
	var $error_msg;
	/**
	 * Grab instance of object.
	 * @return SolrPower_Sync
	 */
	public static function get_instance() {
		if ( !self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	function __construct() {
		add_action( 'publish_post', array( $this, 'handle_modified' ) );
		add_action( 'publish_page', array( $this, 'handle_modified' ) );
		add_action( 'save_post', array( $this, 'handle_modified' ) );
		add_action( 'delete_post', array( $this, 'handle_delete' ) );
		if ( is_multisite() ) {
			add_action( 'deactivate_blog', array( $this, 'delete_blog' ) );
			add_action( 'activate_blog', array( $this, 'handle_activate_blog' ) );
			add_action( 'archive_blog', array( $this, 'delete_blog' ) );
			add_action( 'unarchive_blog', array( $this, 'handle_activate_blog' ) );
			add_action( 'make_spam_blog', array( $this, 'delete_blog' ) );
			add_action( 'unspam_blog', array( $this, 'handle_activate_blog' ) );
			add_action( 'delete_blog', array( $this, 'delete_blog' ) );
			add_action( 'wpmu_new_blog', array( $this, 'handle_activate_blog' ) );
		}
	}

	function handle_modified( $post_id ) {
		global $current_blog;

		$post_info = get_post( $post_id );

		$plugin_s4wp_settings	 = solr_options();
		$index_pages			 = $plugin_s4wp_settings[ 's4wp_index_pages' ];
		$index_posts			 = $plugin_s4wp_settings[ 's4wp_index_posts' ];
		$this->handle_status_change( $post_id, $post_info );
		if ( $post_info->post_type == 'revision' || $post_info->post_status != 'publish' ) {
			return;
		}
		$index_posts = $plugin_s4wp_settings[ 's4wp_index_posts' ];
		$this->handle_status_change( $post_id, $post_info );
		if ( $post_info->post_type == 'revision' || $post_info->post_status != 'publish' ) {
			return;
		}
		# make sure this blog is not private or a spam if indexing on a multisite install
		if ( is_multisite() && ($current_blog->public != 1 || $current_blog->spam == 1 || $current_blog->archived == 1) ) {
			return;
		}
		$docs	 = array();
		$solr	 = get_solr();
		$update	 = $solr->createUpdate();
		$doc	 = $this->build_document( $update->createDocument(), $post_info );

		if ( $doc ) {
			$docs[] = $doc;
			$this->post( $docs );
		}

		return;
	}

	function handle_delete( $post_id ) {
		global $current_blog;
		$post_info				 = get_post( $post_id );
		$plugin_s4wp_settings	 = solr_options();
		$delete_page			 = $plugin_s4wp_settings[ 's4wp_delete_page' ];
		$delete_post			 = $plugin_s4wp_settings[ 's4wp_delete_post' ];


		if ( is_multisite() ) {
			$this->delete( get_current_blog_id() . '_' . $post_info->ID );
		} else {
			$this->delete( $post_info->ID );
		}
	}

	function handle_activate_blog( $blogid ) {
		$this->apply_config_to_blog( $blogid );
		$this->load_blog_all( $blogid );
	}

	function delete_blog( $blogid ) {
		try {
			$solr = get_solr();
			if ( !$solr == NULL ) {
				$update = $solr->createUpdate();
				$update->addDeleteQuery( "blogid:{$blogid}" );
				$update->addCommit();
				$solr->update( $update );
			}
		} catch ( Exception $e ) {
			echo esc_html( $e->getMessage() );
		}
	}

	function apply_config_to_blog( $blogid ) {
		syslog( LOG_ERR, "applying config to blog with id $blogid" );
		if ( !is_multisite() )
			return;

		wp_cache_flush();
		$plugin_s4wp_settings = solr_options();
		switch_to_blog( $blogid );
		wp_cache_flush();
		s4wp_update_option( $plugin_s4wp_settings );
		restore_current_blog();
		wp_cache_flush();
	}

	function load_blog_all( $blogid ) {
		global $wpdb;
		$documents	 = array();
		$cnt		 = 0;
		$batchsize	 = 10;

		$bloginfo = get_blog_details( $blogid, FALSE );

		if ( $bloginfo->public && !$bloginfo->archived && !$bloginfo->spam && !$bloginfo->deleted ) {
			$query	 = $wpdb->prepare( "SELECT ID FROM %s WHERE post_type = 'post' and post_status = 'publish';", $wpdb->base_prefix . $blogid . '_posts' );
			$postids = $wpdb->get_results( $query );

			$solr	 = get_solr();
			$update	 = $solr->createUpdate();

			for ( $idx = 0; $idx < count( $postids ); $idx++ ) {
				$postid		 = $ids[ $idx ];
				$documents[] = $this->build_document( $update->createDocument(), get_blog_post( $blogid, $postid->ID ), $bloginfo->domain, $bloginfo->path );
				$cnt++;
				if ( $cnt == $batchsize ) {
					$this->post( $documents );
					$cnt		 = 0;
					$documents	 = array();
				}
			}

			if ( $documents ) {
				$this->post( $documents );
			}
		}
	}

	function handle_status_change( $post_id, $post_info = null ) {
		global $current_blog;

		if ( !$post_info ) {
			$post_info = get_post( $post_id );
		}


		if ( is_multisite() ) {
			$this->delete( get_current_blog_id() . '_' . $post_info->ID );
		} else {
			$this->delete( $post_info->ID );
		}
	}

	function build_document( Solarium\QueryType\Update\Query\Document\Document $doc, $post_info, $domain = NULL,
						  $path = NULL ) {
		$plugin_s4wp_settings	 = solr_options();
		$exclude_ids			 = (is_array( $plugin_s4wp_settings[ 's4wp_exclude_pages' ] )) ? $plugin_s4wp_settings[ 's4wp_exclude_pages' ] : explode( ',', $plugin_s4wp_settings[ 's4wp_exclude_pages' ] );
		$categoy_as_taxonomy	 = $plugin_s4wp_settings[ 's4wp_cat_as_taxo' ];
		$index_comments			 = $plugin_s4wp_settings[ 's4wp_index_comments' ];
		$index_custom_fields	 = (is_array( $plugin_s4wp_settings[ 's4wp_index_custom_fields' ] )) ? $plugin_s4wp_settings[ 's4wp_index_custom_fields' ] : explode( ',', $plugin_s4wp_settings[ 's4wp_index_custom_fields' ] );

		if ( $post_info ) {

			# check if we need to exclude this document
			if ( is_multisite() && in_array( substr( site_url(), 7 ) . $post_info->ID, (array) $exclude_ids ) ) {
				return NULL;
			} else if ( !is_multisite() && in_array( $post_info->ID, (array) $exclude_ids ) ) {
				return NULL;
			}

			$auth_info = get_userdata( $post_info->post_author );

			# wpmu specific info
			if ( is_multisite() ) {
				// if we get here we expect that we've "switched" what blog we're running
				// as
				global $current_blog;

				$blogid = get_current_blog_id();
				$doc->setField( 'solr_id', $blogid . '_' . $post_info->ID );
				$doc->setField( 'blogid', $blogid );
				$doc->setField( 'blogdomain', $domain );
				$doc->setField( 'blogpath', $path );
				$doc->setField( 'wp', 'multisite' );
			} else {
				$doc->setField( 'solr_id', $post_info->ID );
			}
			$doc->setField( 'ID', $post_info->ID );
			$doc->setField( 'permalink', get_permalink( $post_info->ID ) );
			$doc->setField( 'wp', 'wp' );


			$numcomments = 0;
			if ( $index_comments ) {
				$comments = get_comments( "status=approve&post_id={$post_info->ID}" );
				foreach ( $comments as $comment ) {
					$doc->addField( 'comments', $comment->comment_content );
					$numcomments += 1;
				}
			}
			$doc->setField( 'post_name', $post_info->post_name );
			$doc->setField( 'post_title', $post_info->post_title );
			$doc->setField( 'post_content', strip_tags( $post_info->post_content ) );
			$doc->setField( 'comment_count', $numcomments );
			if ( isset( $auth_info->display_name ) ) {
				$doc->setField( 'post_author', $auth_info->display_name );
			}
			if ( isset( $auth_info->user_nicename ) ) {
				$doc->setField( 'author_s', get_author_posts_url( $auth_info->ID, $auth_info->user_nicename ) );
			}
			$doc->setField( 'post_type', $post_info->post_type );
			$doc->setField( 'post_date_gmt', $this->format_date( $post_info->post_date_gmt ) );
			$doc->setField( 'post_modified_gmt', $this->format_date( $post_info->post_modified_gmt ) );
			$doc->setField( 'post_date', $this->format_date( $post_info->post_date ) );
			$doc->setField( 'post_modified', $this->format_date( $post_info->post_modified ) );
			$doc->setField( 'displaydate', $post_info->post_date );
			$doc->setField( 'displaymodified', $post_info->post_modified );


			$doc->setField( 'post_status', $post_info->post_status );
			$doc->setField( 'post_parent', $post_info->post_parent );
			$doc->setField( 'post_excerpt', $post_info->post_excerpt );
			$doc->setField( 'post_status', $post_info->post_status );

			$categories = get_the_category( $post_info->ID );
			if ( !$categories == NULL ) {
				foreach ( $categories as $category ) {
					if ( $categoy_as_taxonomy ) {
						$doc->addField( 'categories', get_category_parents( $category->cat_ID, FALSE, '^^' ) );
					} else {
						$doc->addField( 'categories', $category->cat_name );
					}
				}
			}

			//get all the taxonomy names used by wp
			$taxonomies = (array) get_taxonomies( array( '_builtin' => FALSE ), 'names' );
			foreach ( $taxonomies as $parent ) {
				$terms = get_the_terms( $post_info->ID, $parent );
				if ( (array) $terms === $terms ) {
					//we are creating *_taxonomy as dynamic fields using our schema
					//so lets set up all our taxonomies in that format
					$parent = $parent . "_taxonomy";
					foreach ( $terms as $term ) {
						$doc->addField( $parent, $term->name );
					}
				}
			}

			$tags = get_the_tags( $post_info->ID );
			if ( !$tags == NULL ) {
				foreach ( $tags as $tag ) {
					$doc->addField( 'tags', $tag->name );
				}
			}

			if ( count( $index_custom_fields ) > 0 && count( $custom_fields = get_post_custom( $post_info->ID ) ) ) {
				foreach ( (array) $index_custom_fields as $field_name ) {
					// test a php error notice.
					if ( isset( $custom_fields[ $field_name ] ) ) {
						$field = (array) $custom_fields[ $field_name ];
						foreach ( $field as $key => $value ) {
							$doc->addField( $field_name . '_str', $value );
							$doc->addField( $field_name . '_srch', $value );
						}
					}
				}
			}
		} else {
			// this will fire during blog sign up on multisite, not sure why
			_e( 'Post Information is NULL', 'solr4wp' );
		}

		return $doc;
	}

	function post( $documents, $commit = TRUE, $optimize = FALSE ) {
		try {
			$solr = get_solr();
			if ( !$solr == NULL ) {

				$update = $solr->createUpdate();

				if ( $documents ) {
					syslog( LOG_INFO, "posting " . count( $documents ) . " documents for blog:" . get_bloginfo( 'wpurl' ) );
					$update->addDocuments( $documents );
				} else {
					syslog( LOG_INFO, "posting failed documents for blog:" . get_bloginfo( 'wpurl' ) );
				}

				if ( $commit ) {
					syslog( LOG_INFO, "telling Solr to commit" );
					$update->addCommit();
					$solr->update( $update );
				}

				if ( $optimize ) {
					$update = $solr->createUpdate();
					$update->addOptimize();
					$solr->update( $update );
					syslog( LOG_INFO, "Optimizing: " . get_bloginfo( 'wpurl' ) );
				}
				wp_cache_delete( 'solr_index_stats', 'solr' );
			} else {
				syslog( LOG_ERR, "failed to get a solr instance created" );
				$this->error_msg=esc_html( 'failed to get a solr instance created' );
				return false;
			}
		} catch ( Exception $e ) {
			$this->error_msg=esc_html( $e->getMessage() );
			return false;
		}
	}

	function delete( $doc_id ) {
		try {
			$solr = get_solr();
			if ( !$solr == NULL ) {
				$update = $solr->createUpdate();
				$update->addDeleteById( $doc_id );
				$update->addCommit();
				$solr->update( $update );
			}
			return true;
		} catch ( Exception $e ) {
			$this->error_msg=esc_html( $e->getMessage() );
			return false;
		}
		
	}

	function delete_all() {
		try {
			$solr = get_solr();

			if ( !$solr == NULL ) {
				$update = $solr->createUpdate();
				$update->addDeleteQuery( '*:*' );
				$update->addCommit();
				$solr->update( $update );
			}
			wp_cache_delete( 'solr_index_stats', 'solr' );
			return true;
		} catch ( Exception $e ) {
			$this->error_msg=esc_html( $e->getMessage() );
			return false;
		}
	}

	function load_all_posts( $prev, $post_type = 'post', $limit = 5, $echo = true ) {
		global $wpdb, $current_blog, $current_site;
		$documents				 = array();
		$cnt					 = 0;
		$batchsize				 = 500;
		$last					 = "";
		$found					 = FALSE;
		$end					 = FALSE;
		$percent				 = 0;
		//multisite logic is decided s4wp_get_option
		$plugin_s4wp_settings	 = solr_options();
		if ( isset( $blog ) ) {
			$blog_id = $blog->blog_id;
		}
		if ( is_multisite() ) {

			// there is potential for this to run for an extended period of time, depending on the # of blgos
			syslog( LOG_ERR, "starting batch import, setting max execution time to unlimited" );
			ini_set( 'memory_limit', '1024M' );
			set_time_limit( 0 );

			// get a list of blog ids
			$bloglist = $wpdb->get_col( "SELECT * FROM {$wpdb->base_prefix}blogs WHERE spam = 0 AND deleted = 0", 0 );
			syslog( LOG_INFO, "pushing posts from " . count( $bloglist ) . " blogs into Solr" );
			foreach ( $bloglist as $bloginfo ) {

				// for each blog we need to import we get their id
				// and tell wordpress to switch to that blog
				$blog_id = trim( $bloginfo );
				syslog( LOG_INFO, "switching to blogid $blog_id" );

				// attempt to save some memory by flushing wordpress's cache
				wp_cache_flush();

				// everything just works better if we tell wordpress
				// to switch to the blog we're using, this is a multi-site
				// specific function
				switch_to_blog( $blog_id );

				// now we actually gather the blog posts
				$args	 = array(
					'post_type'		 => apply_filters( 'solr_post_types', get_post_types( array( 'exclude_from_search' => false ) ) ),
					'post_status'	 => 'publish',
					'fields'		 => 'ids',
					'posts_per_page' => absint( $limit ),
					'offset'		 => absint( $prev )
				);
				$query	 = new WP_Query( $args );
				$postids = $query->posts;

				$postcount = count( $postids );
				syslog( LOG_INFO, "building $postcount documents for " . substr( get_bloginfo( 'wpurl' ), 7 ) );
				for ( $idx = 0; $idx < $postcount; $idx++ ) {

					$postid	 = $postids[ $idx ];
					$last	 = $postid;
					$percent = (floatval( $idx ) / floatval( $postcount )) * 100;
					if ( $prev && !$found ) {
						if ( $postid === $prev ) {
							$found = TRUE;
						}

						continue;
					}

					if ( $idx === $postcount - 1 ) {
						$end = TRUE;
					}

					// using wpurl is better because it will return the proper
					// URL for the blog whether it is a subdomain install or otherwise
					$solr		 = get_solr();
					$update		 = $solr->createUpdate();
					$documents[] = $this->build_document( $update->createDocument(), get_blog_post( $blog_id, $postid ), substr( get_bloginfo( 'wpurl' ), 7 ), $current_site->path );
					$cnt++;
					if ( $cnt == $batchsize ) {
						$this->post( $documents, true, false );
						$this->post( false, true, false );
						wp_cache_flush();
						$cnt		 = 0;
						$documents	 = array();
					}
				}
				// post the documents to Solr
				// and reset the batch counters
				$this->post( $documents, true, false );
				$this->post( false, true, false );
				$cnt		 = 0;
				$documents	 = array();
				syslog( LOG_INFO, "finished building $postcount documents for " . substr( get_bloginfo( 'wpurl' ), 7 ) );
				wp_cache_flush();
			}

			// done importing so lets switch back to the proper blog id
			restore_current_blog();
		} else {
			$args		 = array(
				'post_type'		 => apply_filters( 'solr_post_types', get_post_types( array( 'exclude_from_search' => false ) ) ),
				'post_status'	 => 'publish',
				'fields'		 => 'ids',
				'posts_per_page' => absint( $limit ),
				'offset'		 => absint( $prev )
			);
			$query		 = new WP_Query( $args );
			$posts		 = $query->posts;
			$postcount	 = count( $posts );
			if ( 0 == $postcount ) {
				$end	 = true;
				$results = sprintf( "{\"type\": \"" . $post_type . "\", \"last\": \"%s\", \"end\": true, \"percent\": \"%.2f\"}", $last, 100 );
				if ( $echo ) {
					echo $results;
				}
				die();
			}
			$last	 = absint( $prev ) + 5;
			$percent = absint( (floatval( $last ) / floatval( $query->found_posts )) * 100 );
			for ( $idx = 0; $idx < $postcount; $idx++ ) {
				$postid = $posts[ $idx ];

				$solr		 = get_solr();
				$update		 = $solr->createUpdate();
				$documents[] = $this->build_document( $update->createDocument(), get_post( $postid ) );
				$cnt++;
				if ( $cnt == $batchsize ) {
					$this->post( $documents, true, FALSE );
					$cnt		 = 0;
					$documents	 = array();
					wp_cache_flush();
					break;
				}
			}
		}

		if ( $documents ) {
			$this->post( $documents, true, FALSE );
		}

		if ( 100 <= $percent ) {
			$results = sprintf( "{\"type\": \"" . $post_type . "\", \"last\": \"%s\", \"end\": true, \"percent\": \"%.2f\"}", $last, 100 );
		} else {
			$results = sprintf( "{\"type\": \"" . $post_type . "\", \"last\": \"%s\", \"end\": false, \"percent\": \"%.2f\"}", $last, $percent );
		}
		if ( $echo ) {
			echo $results;
			return;
		}
		return $results;
	}

	function format_date( $thedate ) {
		$datere	 = '/(\d{4}-\d{2}-\d{2})\s(\d{2}:\d{2}:\d{2})/';
		$replstr = '${1}T${2}Z';
		return preg_replace( $datere, $replstr, $thedate );
	}

	// copies config settings from the main blog
// to all of the other blogs
	function copy_config_to_all_blogs() {
		global $wpdb;

		$blogs = $wpdb->get_results( "SELECT blog_id FROM $wpdb->blogs WHERE spam = 0 AND deleted = 0" );

		$plugin_s4wp_settings = solr_options();
		foreach ( $blogs as $blog ) {
			switch_to_blog( $blog->blog_id );
			wp_cache_flush();
			syslog( LOG_INFO, "pushing config to {$blog->blog_id}" );
			SolrPower_Options::get_instance()->update_option( $plugin_s4wp_settings );
		}

		wp_cache_flush();
		restore_current_blog();
	}

}

SolrPower_Sync::get_instance();
