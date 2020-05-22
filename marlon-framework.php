<?php

/**
 *
 * @since             1.0.0
 * @package           Marlon
 *
 * @wordpress-plugin
 * Plugin Name:       Marlon Framework
 * Plugin URI:        https://github.com/chrisbergr/marlon-framework
 * Description:       This is the Marlon WordPress-Framework
 * Version:           1.0.0
 * Author:            Christian Hockenberger
 * Author URI:        https://christian.hockenberger.us
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       marlon
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'MARLON_FRAMEWORK_VERSION', '1.0.0' );

//add_action( 'plugins_loaded', 'marlon_load_textdomain' );
//function marlon_load_textdomain() {
//	load_plugin_textdomain( 'marlon', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
//}

function activate_marlon_framework() {
	require_once plugin_dir_path( __FILE__ ) . 'framework/core/plugin/class-marlon-activator.php';
	Marlon_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_marlon_framework' );

function deactivate_marlon_framework() {
	require_once plugin_dir_path( __FILE__ ) . 'framework/core/plugin/class-marlon-deactivator.php';
	Marlon_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_marlon_framework' );

require_once plugin_dir_path( __FILE__ ) . 'framework/marlon.php';

function marlon_framework() {
	//TODO: Make modules optional via settings page
	$modules = array(
		//'module_template'      => 'Module_Template',
		'site_tools'            => 'Site_Tools',
		'site_breadcrumbs'      => 'Site_Breadcrumbs',
		'post_kicker'           => 'Post_Kicker',
		'post_subtitle'         => 'Post_Subtitle',
		'post_utilities'        => 'Post_Utilities',
		'post_primary_category' => 'Post_Primary_Category',
		'post_layout'           => 'Post_Layout',
		'theme_settings'        => 'Theme_Settings',
		'dashboard_widgets'     => 'Dashboard_Widgets',
		'marlon_shortcodes'     => 'Marlon_Shortcodes',
	);
	$framework = call_user_func( array( 'Marlon', 'get_instance' ), MARLON_FRAMEWORK_VERSION, plugin_dir_path( __FILE__ ), $modules );
	return $framework;
}

marlon_framework()->run();

//TODO: add the following to new module third party support
function third_party_kind_view_paths( $path_list ) {
	array_unshift( $path_list, plugin_dir_path( __FILE__ ) . 'framework/templates/third-party/kind-views/' );
	return $path_list;
}
add_filter( 'kind_view_paths', 'third_party_kind_view_paths' );

//print_r( '<!-- Marlon v' . marlon_framework()->get_version() . ' -->' );
