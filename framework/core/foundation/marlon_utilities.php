<?php

if ( ! class_exists( 'Marlon_Utilities' ) ) {
	class Marlon_Utilities {

		private $core_version    = '';
		private $plugin_root     = '';
		private $loader          = false;

		public function __construct( $version, $root, $loader ) {
			$this->core_version = $version;
			$this->plugin_root  = $root;
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

	}
}
