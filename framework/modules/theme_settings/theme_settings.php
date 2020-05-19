<?php

if ( ! class_exists( 'Theme_Settings' ) ) {

	require_once plugin_dir_path( __FILE__ ) . 'class-wp-customize-colorrange.php';
	require_once plugin_dir_path( __FILE__ ) . 'class-wp-customize-range.php';

	class Theme_Settings extends Marlon_Module {

		protected function init_module() {
			$this->loader->add_action( 'wp_head', $this, 'customizer_css' );
			$this->loader->add_action( 'customize_preview_init', $this, 'customizer_script' );
			$this->loader->add_action( 'customize_register', $this, 'customizer_settings' );
		}

		public function customizer_css() {
			?>

			<style type="text/css">
				:root {
					--color-primary: <?php echo esc_attr( get_theme_mod( 'marlon_theme_primary_color', '168' ) ); ?>;
					--color-secondary: <?php echo esc_attr( get_theme_mod( 'marlon_theme_secondary_color', '330' ) ); ?>;
					--color-special: <?php echo esc_attr( get_theme_mod( 'marlon_theme_special_color', '275' ) ); ?>;
				}
			</style>

			<?php
		}

		public function customizer_script() {
			wp_enqueue_script(
				'marlon_customizer_theme_settings',
				$this->get_plugin_url() . 'framework/modules/theme_settings/theme_settings.js',
				array( 'jquery', 'customize-preview' ),
				$this->get_version(),
				true
			);
		}

		public function customizer_settings( $wp_customize ) {

			$wp_customize->add_section(
				'marlon_theme_settings',
				array(
					'title'    => 'Marlon Theme Settings',
					'priority' => 30,
				)
			);

			$wp_customize->add_setting(
				'marlon_theme_primary_color',
				array(
					'default'   => 168,
					'transport' => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Colorrange(
					$wp_customize,
					'marlon_theme_primary_color',
					array(
						'label'    => 'Primary Color',
						'min'      => 0,
						'max'      => 360,
						'step'     => 1,
						'section'  => 'marlon_theme_settings',
						'settings' => 'marlon_theme_primary_color',
						'default'  => 168,
					)
				)
			);

			$wp_customize->add_setting(
				'marlon_theme_secondary_color',
				array(
					'default'   => 330,
					'transport' => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Colorrange(
					$wp_customize,
					'marlon_theme_secondary_color',
					array(
						'label'    => 'Secondary Color',
						'min'      => 0,
						'max'      => 360,
						'step'     => 1,
						'section'  => 'marlon_theme_settings',
						'settings' => 'marlon_theme_secondary_color',
						'default'  => 330,
					)
				)
			);

			$wp_customize->add_setting(
				'marlon_theme_special_color',
				array(
					'default'   => 275,
					'transport' => 'postMessage',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Colorrange(
					$wp_customize,
					'marlon_theme_special_color',
					array(
						'label'    => 'Special Color',
						'min'      => 0,
						'max'      => 360,
						'step'     => 1,
						'section'  => 'marlon_theme_settings',
						'settings' => 'marlon_theme_special_color',
						'default'  => 275,
					)
				)
			);

			$wp_customize->add_setting(
				'marlon_theme',
				array(
					'default'   => 'bright',
					'transport' => 'postMessage',
				)
			);

			$wp_customize->add_control(
				'marlon_theme',
				array(
					'label'    => 'Theme',
					'type'     => 'radio',
					'choices'  => array(
						'bright' => 'Bright Theme',
						'dark'   => 'Dark Theme',
						'debug'  => 'Debug Theme',
					),
					'section'  => 'marlon_theme_settings',
					'settings' => 'marlon_theme',
				)
			);

		}

	}
}
