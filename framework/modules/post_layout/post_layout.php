<?php

if ( ! class_exists( 'Post_Layout' ) ) {
	class Post_Layout extends Marlon_Module {

		private $post_id = 0;
		private $post = null;

		protected function init_module() {
			$this->loader->add_action( 'admin_menu', $this, 'metabox_add' );
			$this->loader->add_action( 'save_post', $this, 'metabox_save', 10, 2 );
			add_post_type_support( 'page', 'marlon_post_layout' );
			add_post_type_support( 'post', 'marlon_post_layout' );
			add_post_type_support( 'revision', 'marlon_post_layout' );
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

		private function get_supported_post_types() {
			$post_types = (array) get_post_types( array(
				'_builtin' => false
			) );
			$post_types = array_merge( $post_types, array( 'post', 'page', 'revision' ) );
			$supported = array();
			foreach ( $post_types as $post_type ) {
				if ( post_type_supports( $post_type, 'marlon_post_layout' ) ) {
					$supported[] = $post_type;
				}
			}
			return $supported;
		}

		private function is_supported_post_type() {
			$post_types = $this->get_supported_post_types();
			return in_array( get_post_type( $this->post_id ), $post_types );
		}

		public function metabox_add() {
			add_meta_box(
				'marlon_post_layout',
				esc_html__( 'Marlon Post Layout', 'marlon' ),
				array( $this, 'metabox_display' ),
				array( 'post', 'page', 'revision' ),
				'side',
				'low'
			);
		}

		public function metabox_display( $post ) {

			wp_nonce_field( basename( __FILE__ ), 'marlon_post_layout_nonce' );

			$layout = get_post_meta( $post->ID, 'marlon_post_layout', true );
			if( empty( $layout ) ) {
				$layout = 'layout-default';
			}
			?>
			<div class="marlon-inside">
				<div id="specific-page-layout">
					<fieldset>

						<label for="has-default-layout" class="has-default-layout">
							<input type="radio" name="marlon_post_layout" id="has-default-layout" value="layout-default" <?php echo checked( $layout, 'layout-default', false ); ?>>
							<?php echo esc_html__( 'Default Layout', 'marlon' ); ?>
						</label>

						<label for="has-headercover-layout" class="has-headercover-layout">
							<input type="radio" name="marlon_post_layout" id="has-headercover-layout" value="layout-headercover" <?php echo checked( $layout, 'layout-headercover', false ); ?>>
							<?php echo esc_html__( 'Layout Cover Header', 'marlon' ); ?>
						</label>

					</fieldset>
				</div>
			</div>
			<?php

		}

		public function metabox_save( $post_id, $post ) {
			if( ! isset( $_POST[ 'marlon_post_layout_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'marlon_post_layout_nonce' ], basename( __FILE__ ) ) ) {
				return $post_id;
			}
			$post_type = get_post_type_object( $post->post_type );
			if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}
			if( is_multisite() && ms_is_switched() ) {
				return $post_id;
			}
			if( 'page' == $_POST['post_type'] ) {
				if( ! current_user_can( 'edit_page', $post_id ) ) {
					return $post_id;
				}
			} elseif( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
			if( isset( $_POST['marlon_post_layout'] ) ) {
				update_post_meta( $post_id, 'marlon_post_layout', sanitize_text_field( $_POST['marlon_post_layout'] ) );
			} else {
				delete_post_meta( $post_id, 'marlon_post_layout' );
			}
			return $post_id;
		}

	}
}
