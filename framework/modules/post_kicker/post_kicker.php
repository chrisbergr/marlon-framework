<?php

if ( ! class_exists( 'Post_Kicker' ) ) {
	class Post_Kicker extends Marlon_Module {

		private $post_id = 0;
		private $post = null;

		protected function init_module() {
			$this->loader->add_action( 'admin_menu', $this, 'metabox_add' );
			$this->loader->add_action( 'save_post', $this, 'metabox_save', 10, 2 );
			add_post_type_support( 'page', 'marlon_kicker' );
			add_post_type_support( 'post', 'marlon_kicker' );
			add_post_type_support( 'revision', 'marlon_kicker' );
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

		private function kicker_get( $data, $post_id = false ) {
			return $data;
		}

		private function kicker_print( $data, $echo = true ) {
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
				if ( post_type_supports( $post_type, 'marlon_kicker' ) ) {
					$supported[] = $post_type;
				}
			}
			return $supported;
		}

		private function is_supported_post_type() {
			$post_types = $this->get_supported_post_types();
			return in_array( get_post_type( $this->post_id ), $post_types );
		}

		private function get_raw_kicker() {
			if ( is_preview() ) {
				if ( isset( $_GET['preview_id'] ) ) {
					$p = wp_get_post_autosave( $this->post_id );
					return get_post_meta( $p->ID, 'wpk_kicker', true );
				}
				if ( $revisions = wp_get_post_revisions( $this->post_id ) ) {
					$p = array_shift( $revisions );
					return get_post_meta( $p->ID, 'wpk_kicker', true );
				}
			}
			return get_post_meta( $this->post_id, 'wpk_kicker', true );
		}

		private function get_kicker( $args = '', $post_id = false ) {

			$this->setup_module( $post_id );

			if ( $this->post_id && $this->is_supported_post_type() ) {

				$args = wp_parse_args( $args, array(
					'before' => '',
					'after'  => ''
				) );

				$kicker = apply_filters( 'marlon_kicker', $this->get_raw_kicker(), get_post( $this->post_id ) );

				if ( ! empty( $kicker ) ) {
					$kicker = $args['before'] . $kicker . $args['after'];
				}

				return $kicker;
			}

			return '';

		}
		public function get_the_kicker( $before = '', $after = '', $post_id = false ) {
			return $this->kicker_get(
				$this->get_kicker(
					array(
						'before' => $before,
						'after'  => $after,
					),
					$post_id
				)
			);
		}
		public function the_kicker( $before = '', $after = '', $echo = true ) {
			return $this->kicker_print( $this->get_the_kicker( $before, $after, get_the_ID() ), $echo );
		}

		public function metabox_add() {
			add_meta_box(
				'marlon_kicker',
				esc_html__( 'Marlon Kicker', 'marlon' ),
				array( $this, 'metabox_display' ),
				array( 'post', 'page', 'revision' ),
				'side',
				'low'
			);
		}

		public function metabox_display( $post ) {
			wp_nonce_field( basename( __FILE__ ), 'marlon_kicker_nonce' );
			$html = '';
			$html .= '<p style="margin-top:15px;">';
			$html .= '<input class="widefat" type="text" name="marlon_kicker" id="marlon_kicker" value="';
			$html .= esc_attr( get_post_meta( $post->ID, 'wpk_kicker', true ) );
			$html .= '" size="30" /></p>';
			echo $html;
		}

		public function metabox_save( $post_id, $post ) {
			if ( ! isset( $_POST[ 'marlon_kicker_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'marlon_kicker_nonce' ], basename( __FILE__ ) ) )
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
			update_post_meta( $post_id, 'wpk_kicker', $_POST[ 'marlon_kicker' ] );
			update_post_meta( $post_id, 'wpk_kicker', $_POST[ 'marlon_kicker' ] );
			return $post_id;
		}

	}
}
