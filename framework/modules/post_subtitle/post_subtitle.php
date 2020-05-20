<?php

if ( ! class_exists( 'Post_Subtitle' ) ) {
	class Post_Subtitle extends Marlon_Module {

		private $post_id = 0;
		private $post = null;

		protected function init_module() {
			$this->loader->add_action( 'admin_menu', $this, 'metabox_add' );
			$this->loader->add_action( 'save_post', $this, 'metabox_save', 10, 2 );
			add_post_type_support( 'page', 'marlon_subtitle' );
			add_post_type_support( 'post', 'marlon_subtitle' );
			add_post_type_support( 'revision', 'marlon_subtitle' );
		}

		public function setup_module( $post ) {
			if ( ! $post ) {
				$post = get_the_ID();
			}
			if ( is_a( $post, 'WP_Post' ) ) {
				$this->post_id = absint( $post->ID );
				$this->post    = $post;
			} else {
				$this->post_id = absint( $post );
				$this->post    = get_post( $this->post_id );
			}
		}

		private function subtitle_get( $data, $post_id = false ) {
			return $data;
		}

		private function subtitle_print( $data, $echo = true ) {
			if ( ! $echo ) {
				return $data;
			}
			echo $data;
		}

		private function get_supported_post_types() {
			$post_types = (array) get_post_types( array(
				'_builtin' => false
			) );
			$post_types = array_merge( $post_types, array( 'post', 'page', 'revision' ) );
			$supported = array();
			foreach ( $post_types as $post_type ) {
				if ( post_type_supports( $post_type, 'marlon_subtitle' ) ) {
					$supported[] = $post_type;
				}
			}
			return $supported;
		}

		private function is_supported_post_type() {
			$post_types = $this->get_supported_post_types();
			return in_array( get_post_type( $this->post_id ), $post_types );
		}

		private function get_raw_subtitle() {
			if ( is_preview() ) {
				if ( isset( $_GET['preview_id'] ) ) {
					$p = wp_get_post_autosave( $this->post_id );
					return get_post_meta( $p->ID, 'wps_subtitle', true );
				}
				if ( $revisions = wp_get_post_revisions( $this->post_id ) ) {
					$p = array_shift( $revisions );
					return get_post_meta( $p->ID, 'wps_subtitle', true );
				}
			}
			return get_post_meta( $this->post_id, 'wps_subtitle', true );
		}

		private function get_subtitle( $args = '', $post_id = false ) {

			$this->setup_module( $post_id );

			if ( $this->post_id && $this->is_supported_post_type() ) {

				$args = wp_parse_args( $args, array(
					'before' => '',
					'after'  => ''
				) );

				$subtitle = apply_filters( 'marlon_subtitle', $this->get_raw_subtitle(), get_post( $this->post_id ) );

				if ( ! empty( $subtitle ) ) {
					$subtitle = $args['before'] . $subtitle . $args['after'];
				}

				return $subtitle;
			}

			return '';

		}
		public function get_the_subtitle( $before = '', $after = '', $post_id = false ) {
			return $this->subtitle_get(
				$this->get_subtitle(
					array(
						'before' => $before,
						'after'  => $after,
					),
					$post_id
				)
			);
		}
		public function the_subtitle( $before = '', $after = '', $echo = true ) {
			return $this->subtitle_print( $this->get_the_subtitle( $before, $after, get_the_ID() ), $echo );
		}

		public function metabox_add() {
			add_meta_box(
				'marlon_subtitle',
				esc_html__( 'Marlon Subtitle', 'marlon' ),
				array( $this, 'metabox_display' ),
				array( 'post', 'page', 'revision' ),
				'side',
				'low'
			);
		}

		public function metabox_display( $post ) {
			wp_nonce_field( basename( __FILE__ ), 'marlon_subtitle_nonce' );
			$html = '';
			$html .= '<p style="margin-top:15px;">';
			$html .= '<input class="widefat" type="text" name="marlon_subtitle" id="marlon_subtitle" value="';
			$html .= esc_attr( get_post_meta( $post->ID, 'wps_subtitle', true ) );
			$html .= '" size="30" /></p>';
			echo $html;
		}

		public function metabox_save( $post_id, $post ) {
			if ( ! isset( $_POST[ 'marlon_subtitle_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'marlon_subtitle_nonce' ], basename( __FILE__ ) ) )
				return $post_id;
			$post_type = get_post_type_object( $post->post_type );
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return $post_id;
			if ( is_multisite() && ms_is_switched() )
				return $post_id;
			if( 'page' == $_POST['post_type'] ) {
				if( ! current_user_can( 'edit_page', $post_id ) )
					return $post_id;
			}
			elseif( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
			update_post_meta( $post_id, 'wps_subtitle', $_POST[ 'marlon_subtitle' ] );
			return $post_id;
		}

	}
}
