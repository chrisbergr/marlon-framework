<?php

if ( ! class_exists( 'Marlon' ) ) {
	class Marlon {

		private static $instance = null;
		private $core_version    = '';
		private $plugin_root     = '';
		protected $loader        = null;
		private $modules         = array();

		private function __clone() {}
		private function __wakeup() {}

		public function __construct( $version, $root, $modules = array() ) {
			$this->core_version = $version;
			$this->plugin_root  = $root;
			$this->plugin_url   = plugin_dir_url( __DIR__ );
			$this->load_dependencies();
			$this->load_modules( $modules );
			$this->loader->add_action( 'wp_enqueue_scripts', $this, 'enqueue_scripts', 9 );
		}

		public function enqueue_scripts() {
			wp_register_script(
				'marlon-framework',
				$this->get_plugin_url() . 'assets/marlon-framework.min.js',
				array( 'jquery' ),
				$this->get_version(),
				true
			);
			wp_enqueue_script( 'marlon-framework' );
			wp_register_style(
				'marlon-framework',
				$this->get_plugin_url() . 'assets/marlon-framework.min.css',
				array(),
				$this->get_version(),
				'all'
			);
			wp_enqueue_style( 'marlon-framework' );
		}

		function locate_template( $template_names, $load = false, $require_once = true ) {
			$located = '';
			foreach ( (array) $template_names as $template_name ) {
				if ( ! $template_name ) {
					continue;
				}
				if ( file_exists( $this->get_plugin_root() . 'framework/templates/' . $template_name ) ) {
					$located = $this->get_plugin_root() . 'framework/templates/' . $template_name;
					break;
				} elseif ( file_exists( STYLESHEETPATH . '/' . $template_name ) ) {
					$located = STYLESHEETPATH . '/' . $template_name;
					break;
				} elseif ( file_exists( TEMPLATEPATH . '/' . $template_name ) ) {
					$located = TEMPLATEPATH . '/' . $template_name;
					break;
				} elseif ( file_exists( ABSPATH . WPINC . '/theme-compat/' . $template_name ) ) {
					$located = ABSPATH . WPINC . '/theme-compat/' . $template_name;
					break;
				}
			}
			if ( $load && '' != $located ) {
				load_template( $located, $require_once );
			}
			return $located;
		}

		public function get_template_part( $slug, $name = null ) {
			do_action( "get_template_part_{$slug}", $slug, $name );
			$templates = array();
			$name      = (string) $name;
			if ( '' !== $name ) {
				$templates[] = "{$slug}-{$name}.php";
			}
			$templates[] = "{$slug}.php";
			do_action( 'get_template_part', $slug, $name, $templates );
			$this->locate_template( $templates, true, false );
		}

		private function load_dependencies() {
			require_once $this->get_plugin_root() . 'framework/core/foundation/marlon_loader.php';
			require_once $this->get_plugin_root() . 'framework/core/foundation/marlon_utilities.php';
			require_once $this->get_plugin_root() . 'framework/core/foundation/marlon_module.php';
			$this->loader = new Marlon_Loader();
		}

		private function load_modules( $modules ) {
			foreach ( $modules as $module => $name ) {
				$loading_module = $this->load_module( $module );
				if ( $loading_module ) {
					$this->modules[$module] = new $name( $this->get_version(), $this->get_plugin_root(), $this->loader );
				}
			}
		}

		private function load_module( $module ) {
			if ( $this->has_module( $module ) ) {
				return false;
			}
			$module_file = $this->get_plugin_root() . 'framework/modules/' . $module . '/' . $module . '.php';
			if ( ! file_exists( $module_file ) ) {
				return false;
			}
			require_once $module_file;
			return true;
		}

		public function get_module( $module ) {
			if ( $this->has_module( $module ) ) {
				return $this->modules[$module];
			}
			return false;
		}

		public function has_module( $module ) {
			return array_key_exists( $module, $this->modules );
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

		public function run() {
			return $this->loader->run();
		}

		public static function get_instance( $version, $root, $modules = array() ) {
			if ( null === self::$instance ) {
				self::$instance = new self( $version, $root, $modules );
			}
			return self::$instance;
		}

	}
}
