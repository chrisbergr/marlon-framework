<?php

if ( ! class_exists( 'Marlon_Utilities' ) ) {
	class Marlon_Utilities {

		private $core_version = '';
		private $plugin_root  = '';
		private $plugin_url   = '';
		protected $loader     = false;

		public function __construct( $version, $root, $url, $loader ) {
			$this->core_version = $version;
			$this->plugin_root  = $root;
			$this->plugin_url   = $url;
			$this->loader       = $loader;
			return $this->init();
		}

		protected function init() {
			//NOTE: overwrite in other foundation class
		}

		protected function is_third_party_active( $plugin ) {
			return in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
		}

		public function get_marlon_template( $slug, $name = null ) {

			$template = '';
			$name     = (string) $name;
			if ( '' !== $name ) {
				$locations[] = "/marlon/{$slug}-{$name}.php";
			}
			$locations[] = "/marlon/{$slug}.php";

			$template = locate_template( $locations, false );

			if ( empty( $template ) ) {
				if ( '' !== $name && file_exists( $this->get_plugin_root() . 'framework/templates/marlon/' . $slug . '-' . $name . '.php' ) ) {
					$template = $this->get_plugin_root() . 'framework/templates/marlon/' . $slug . '-' . $name . '.php';
				} elseif ( file_exists( $this->get_plugin_root() . 'framework/templates/marlon/' . $slug . '.php' ) ) {
					$template = $this->get_plugin_root() . 'framework/templates/marlon/' . $slug . '.php';
				}
			}

			if ( '' != $template ) {
				load_template( $template, false );
			}

			return $template;

		}

		public function get_version() {
			return $this->core_version;
		}
		public function get_plugin_root() {
			return $this->plugin_root;
		}
		public function get_plugin_url() {
			return $this->plugin_url;
		}

		public function get_post_count_by_tax_term( $term, $taxonomy, $type = 'post' ) {
			$args = array (
				'fields'         =>'ids',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'post_type'      => $type,
				'tax_query'      => array (
					array (
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => $term,
					),
				),
			);
			if ( $posts = get_posts( $args ) ) {
				return count( $posts );
			}
			return 0;
		}

		public function get_post_count_by_post_format( $format ) {
			return $this->get_post_count_by_tax_term( 'post-format-' . $format, 'post_format' );
		}

		public function get_post_format_status_count() {
			return $this->get_post_count_by_post_format( 'status' );
		}
		public function get_post_format_aside_count() {
			return $this->get_post_count_by_post_format( 'aside' );
		}
		public function get_post_format_image_count() {
			return $this->get_post_count_by_post_format( 'image' );
		}
		public function get_post_format_video_count() {
			return $this->get_post_count_by_post_format( 'video' );
		}
		public function get_post_format_audio_count() {
			return $this->get_post_count_by_post_format( 'audio' );
		}
		public function get_post_format_quote_count() {
			return $this->get_post_count_by_post_format( 'quote' );
		}
		public function get_post_format_gallery_count() {
			return $this->get_post_count_by_post_format( 'gallery' );
		}

		/* Post Kinds Support */

		public function get_postkinds_data( $key = false, $id = false) {
			$return = '';
			if( ! $key || ! $id ) {
				return $return;
			}
			$kindsdata = get_post_meta( get_the_id(), 'mf2_in-reply-to', true );
			if( '' === $kindsdata ) {
				$kindsdata = get_post_meta( get_the_id(), 'mf2_repost-of', true );
			}
			if( '' === $kindsdata ) {
				$kindsdata = get_post_meta( get_the_id(), 'mf2_bookmark-of', true );
			}
			if( '' === $kindsdata ) {
				$kindsdata = get_post_meta( get_the_id(), 'mf2_favorite-of', true );
			}
			if( '' === $kindsdata ) {
				$kindsdata = get_post_meta( get_the_id(), 'mf2_like-of', true );
			}
			$kindsdata = maybe_unserialize( $kindsdata );

			if( isset( $kindsdata[$key] ) ) {
				$return = $kindsdata[$key];
			}

			return $return;
		}

		public function get_postkinds_data_type( $id = false ) {
			return $this->get_postkinds_data( 'type', $id )[0];
		}

		public function get_postkinds_data_properties( $id = false ) {
			return $this->get_postkinds_data( 'properties', $id );
		}

		public function get_postkinds_data_property( $key = false, $id = false ) {
			$return = '';
			if( ! $key || ! $id ) {
				return $return;
			}
			$properties = $this->get_postkinds_data_properties( $id );
			if( '' !== $properties && isset( $properties[$key] ) && ( isset( $properties[$key][0] ) || isset( $properties[$key]['properties'] ) ) ) {
				if( 'author' === $key ) {
					$author_data = array_map( 'current', $properties[$key]['properties'] );
					$return = $author_data;
				} else {
					$return = $properties[$key][0];
				}
			}
			return $return;
		}

		public function get_postkinds_data_debug( $id = false) {
			//print_r(get_post_meta( get_the_id() ) );
			$kindsdata = get_post_meta( get_the_id(), 'mf2_in-reply-to', true );
			if( '' === $kindsdata ) {
				$kindsdata = get_post_meta( get_the_id(), 'mf2_repost-of', true );
			}
			if( '' === $kindsdata ) {
				$kindsdata = get_post_meta( get_the_id(), 'mf2_bookmark-of', true );
			}
			if( '' === $kindsdata ) {
				$kindsdata = get_post_meta( get_the_id(), 'mf2_favorite-of', true );
			}
			if( '' === $kindsdata ) {
				$kindsdata = get_post_meta( get_the_id(), 'mf2_like-of', true );
			}
			$kindsdata = maybe_unserialize( $kindsdata );
			//print_r($kindsdata);
			echo '<dl><dt>type</dt><dd>' . $this->get_postkinds_data_type( $id ) . '</dd></dl>';
			/**/
			$properties = $this->get_postkinds_data_properties( $id );
			//print_r($properties);
			echo '<dl>';
			foreach ($properties as $the_key => $value) {
				echo '<dt>' . $the_key . '</dt>';
				echo '<dd>' . $this->get_postkinds_data_property( $the_key, $id ) . '</dd>';
			}
			echo '</dl>';
			//print_r($this->get_postkinds_data_property( 'author', $id ));
			/**/
		}

	}
}
