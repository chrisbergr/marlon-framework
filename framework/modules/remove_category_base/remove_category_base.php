<?php

if ( ! class_exists( 'Remove_Category_Base' ) ) {
	class Remove_Category_Base extends Marlon_Module {

		protected function init_module() {
			$this->loader->add_action( 'created_category', $this, 'remove_category_base_refresh_rules' );
			$this->loader->add_action( 'delete_category', $this, 'remove_category_base_refresh_rules' );
			$this->loader->add_action( 'edited_category', $this, 'remove_category_base_refresh_rules' );
			$this->loader->add_action( 'init', $this, 'remove_category_base_permastruct' );
			$this->loader->add_filter( 'category_rewrite_rules', $this, 'remove_category_base_rewrite_rules' );
			$this->loader->add_filter( 'query_vars', $this, 'remove_category_base_query_vars' );
			$this->loader->add_filter( 'request', $this, 'remove_category_base_request' );
		}

		public function remove_category_base_refresh_rules() {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}

		public function remove_category_base_permastruct() {
			global $wp_rewrite, $wp_version;
			$wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
		}

		function remove_category_base_rewrite_rules( $category_rewrite ) {
			global $wp_rewrite;
			$category_rewrite = array();
			$categories = get_categories( array( 'hide_empty' => false ) );
			foreach ( $categories as $category ) {
				$category_nicename = $category->slug;
				if ( $category->parent == $category->cat_ID ) {
					$category->parent = 0;
				} elseif ( 0 != $category->parent ) {
					$category_nicename = get_category_parents( $category->parent, false, '/', true ) . $category_nicename;
				}
				$category_rewrite[ '(' . $category_nicename . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$' ] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
				$category_rewrite[ '(' . $category_nicename . ')/page/?([0-9]{1,})/?$' ]                  = 'index.php?category_name=$matches[1]&paged=$matches[2]';
				$category_rewrite[ '(' . $category_nicename . ')/?$' ]                                    = 'index.php?category_name=$matches[1]';
			}
			$old_category_base                                 = get_option( 'category_base' ) ? get_option( 'category_base' ) : 'category';
			$old_category_base                                 = trim( $old_category_base, '/' );
			$category_rewrite[ $old_category_base . '/(.*)$' ] = 'index.php?category_redirect=$matches[1]';
			return $category_rewrite;
		}

		public function remove_category_base_query_vars( $public_query_vars ) {
			$public_query_vars[] = 'category_redirect';
			return $public_query_vars;
		}

		public function remove_category_base_request( $query_vars ) {
			if ( isset( $query_vars['category_redirect'] ) ) {
				$catlink = trailingslashit( get_option( 'home' ) ) . user_trailingslashit( $query_vars['category_redirect'], 'category' );
				status_header( 301 );
				header( "Location: $catlink" );
				exit;
			}
			return $query_vars;
		}

	}
}
