<?php

if ( ! class_exists( 'Dashboard_Widgets' ) ) {
	class Dashboard_Widgets extends Marlon_Module {

		protected function init_module() {
			$this->loader->add_action( 'admin_head', $this, 'setup_admin_page' );
			$this->loader->add_action( 'wp_dashboard_setup', $this, 'add_dashboard_widgets' );
		}

		private function is_edit_page() {
			return strpos( $_SERVER['REQUEST_URI'], '/post.php' ) || strpos( $_SERVER['REQUEST_URI'], '/post-new.php' );
		}

		public function setup_admin_page() {
			if( $this->is_edit_page() ) {
				$url = $this->get_plugin_url() . 'framework/modules/dashboard_widgets/dashboard_widgets.js';
				echo '<script type="text/javascript" src="' . $url . '"></script>';
			}
		}

		public function add_dashboard_widgets() {

			$status_count = $this->get_post_format_status_count();
			$aside_count  = $this->get_post_format_aside_count();
			$image_count  = $this->get_post_format_image_count();
			$video_count  = $this->get_post_format_video_count();
			$audio_count  = $this->get_post_format_audio_count();
			$quote_count  = $this->get_post_format_quote_count();

			wp_add_dashboard_widget(
				'marlon_dashboard_widget_note',
				sprintf( _n( '%d Note', '%d Notes', $status_count, 'marlon' ), $status_count ),
				array ( $this, 'dashboard_widget_note' )
			);

			wp_add_dashboard_widget(
				'marlon_dashboard_widget_aside',
				sprintf( _n( '%d Aside Message', '%d Aside Messages', $aside_count, 'marlon' ), $aside_count ),
				array ( $this, 'dashboard_widget_aside' )
			);

			wp_add_dashboard_widget(
				'marlon_dashboard_widget_image',
				sprintf( _n( '%d Image', '%d Images', $image_count, 'marlon' ), $image_count ),
				array ( $this, 'dashboard_widget_image' )
			);

			wp_add_dashboard_widget(
				'marlon_dashboard_widget_video',
				sprintf( _n( '%d Video', '%d Videos', $video_count, 'marlon' ), $video_count ),
				array ( $this, 'dashboard_widget_video' )
			);

			wp_add_dashboard_widget(
				'marlon_dashboard_widget_audio',
				sprintf( _n( '%d Audio Post', '%d Audio Posts', $audio_count, 'marlon' ), $audio_count ),
				array ( $this, 'dashboard_widget_audio' )
			);

			wp_add_dashboard_widget(
				'marlon_dashboard_widget_quote',
				sprintf( _n( '%d Quote', '%d Quotes', $quote_count, 'marlon' ), $quote_count ),
				array ( $this, 'dashboard_widget_quote' )
			);

		}
		function dashboard_widget_note() {
		?>
		<p class="marlon-admin-button-container"><a href="./post-new.php?classic-editor&classic-editor__forget&set_post_format=status" class="marlon-admin-button"><?php _e( 'Create new Note (Status)', 'marlon' ); ?></a></p>
		<p>&nbsp;</p>
		<?php
	}

	function dashboard_widget_aside() {
		?>
		<p class="marlon-admin-button-container"><a href="./post-new.php?classic-editor&classic-editor__forget&set_post_format=aside" class="marlon-admin-button"><?php _e( 'Create new Aside msg', 'marlon' ); ?></a></p>
		<p>&nbsp;</p>
		<?php
	}

	function dashboard_widget_image() {
		?>
		<p class="marlon-admin-button-container"><a href="./post-new.php?classic-editor&classic-editor__forget&set_post_format=image" class="marlon-admin-button"><?php _e( 'Create new Image Post', 'marlon' ); ?></a></p>
		<p>&nbsp;</p>
		<?php
	}

	function dashboard_widget_video() {
		?>
		<p class="marlon-admin-button-container"><a href="./post-new.php?classic-editor&classic-editor__forget&set_post_format=video" class="marlon-admin-button"><?php _e( 'Create new Video Post', 'marlon' ); ?></a></p>
		<p>&nbsp;</p>
		<?php
	}

	function dashboard_widget_audio() {
		?>
		<p class="marlon-admin-button-container"><a href="./post-new.php?classic-editor&classic-editor__forget&set_post_format=audio" class="marlon-admin-button"><?php _e( 'Create new Audio Post', 'marlon' ); ?></a></p>
		<p>&nbsp;</p>
		<?php
	}

	function dashboard_widget_quote() {
		?>
		<p class="marlon-admin-button-container"><a href="./post-new.php?classic-editor&classic-editor__forget&set_post_format=quote" class="marlon-admin-button"><?php _e( 'Create new Quote', 'marlon' ); ?></a></p>
		<p>&nbsp;</p>
		<?php
	}

	}
}
