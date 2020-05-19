<?php

if ( ! class_exists( 'Post_Primary_Category' ) ) {
	class Post_Primary_Category extends Marlon_Module {

		protected function init_module() {
			$this->loader->add_action( 'admin_head', $this, 'setup_admin_page' );
			$this->loader->add_action( 'admin_footer', $this, 'setup_admin_page_data' );
			$this->loader->add_action( 'transition_post_status', $this, 'save_post', -1000, 3 );
			$this->loader->add_filter( 'post_link_category', $this, 'post_link_category', 10, 3 );
		}

		public function post_link_category( $default, $cats, $post ) {
			$primary_category = get_post_meta( $post->ID, '_primary_category', true );
			if( ! $primary_category ) {
				return $default;
			}
			$use_cat = $cats[0];
			for( $i = 0; $i < count( $cats ); $i++ ) {
				if( (int) $cats[$i]->term_id === (int) $primary_category ) {
					$use_cat = $cats[$i];
					break;
				}
			}
			return $use_cat;
		}

		private function is_edit_page() {
			return strpos( $_SERVER['REQUEST_URI'], '/post.php' ) || strpos( $_SERVER['REQUEST_URI'], '/post-new.php' );
		}

		public function setup_admin_page() {
			if( $this->is_edit_page() ) {
				$url = $this->get_plugin_url() . 'framework/modules/post_primary_category/post_primary_category.js';
				echo '<script type="text/javascript" src="' . $url . '"></script>';
				echo '<style type="text/css">.make_primary{ vertical-align:middle; display:none; }</style>';
			}
		}

		public function setup_admin_page_data() {
			if( $this->is_edit_page() ) {
				global $post;
				$category_id = '';
				if( $post->ID ) {
					$category_id = get_post_meta( $post->ID, '_primary_category', true );
				}
				echo '<script type="text/javascript">jQuery( "#categorydiv" ).MarlonPrimaryCategory( { current: "' . $category_id . '" } );</script>';
			}
		}

		public function save_post( $new_status, $old_status, $post ) {

			$post_categories  = wp_get_post_categories( $post->ID );
			$primary_category = false;

			if( array_key_exists( 'marlon_primary_category', $_POST ) ) {
				$primary_category_check = $_POST['marlon_primary_category'];
				foreach( $post_categories as $category_id ) {
					if( (int) $category_id === (int) $primary_category_check ) {
						$primary_category = $category_id;
						break;
					}
				}
			}

			if( $primary_category ) {
				if( ! update_post_meta( $post->ID, '_primary_category', $primary_category ) ) {
					add_post_meta( $post->ID, '_primary_category',  $primary_category, true );
				}
			} else {
				delete_post_meta( $post->ID, '_primary_category' );
			}

		}

	}
}
