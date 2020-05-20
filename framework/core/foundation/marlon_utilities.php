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

	}
}
