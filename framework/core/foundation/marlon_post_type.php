<?php

if( ! class_exists( 'Marlon_Post_Types' ) ) {
	class Marlon_Post_Type extends Marlon_Utilities {

		protected $settings = array(
			'name'           => 'Custom Post Type',
			'plural_label'   => 'Custom Post Types',
			'singular_label' => 'Custom Post Type',
			'slug'           => 'custom-post-type',
		);

		protected $labels = array();

		protected $args = array();

		protected $custom_fields = array();

		public function init() {
			$this->init_post_type();
			$this->post_type_setup_labels();
			$this->post_type_setup_args();
			return $this->activate_post_type();
		}

		protected function init_post_type() {
			//NOTE: overwrite in post type
		}

		protected function post_type_setup_labels() {

			$this->labels = array(
				'name'                  => $this->get_setting( 'plural_label' ), //'Post Type General Name',
				'singular_name'         => $this->get_setting( 'singular_label' ), //'Post Type Singular Name',
				'menu_name'             => $this->get_setting( 'name' ), //'Post Type Menu Name',
				'name_admin_bar'        => $this->get_setting( 'singular_label' ), //'Post Type Admin Bar',
				'archives'              => 'Archives',
				'attributes'            => 'Attributes',
				'parent_item_colon'     => $this->get_setting( 'singular_label', 'Parent ', ':' ), //'Parent Item:',
				'all_items'             => $this->get_setting( 'plural_label', 'All ' ), //'All Items',
				'add_new_item'          => $this->get_setting( 'singular_label', 'Add New ' ), //'Add New Item',
				'add_new'               => $this->get_setting( 'singular_label', 'Add New ' ), //'Add New',
				'new_item'              => $this->get_setting( 'singular_label', 'New ' ), //'New Item',
				'edit_item'             => $this->get_setting( 'singular_label', 'Edit ' ), //'Edit Item',
				'update_item'           => $this->get_setting( 'singular_label', 'Updates ' ), //'Update Item',
				'view_item'             => $this->get_setting( 'singular_label', 'View ' ), //'View Item',
				'view_items'            => $this->get_setting( 'plural_label', 'View ' ), //'View Items',
				'search_items'          => $this->get_setting( 'singular_label', 'Search ' ), //'Search Item',
				'not_found'             => 'Not found',
				'not_found_in_trash'    => 'Not found in Trash',
				'featured_image'        => 'Featured Image',
				'set_featured_image'    => 'Set featured image',
				'remove_featured_image' => 'Remove featured image',
				'use_featured_image'    => 'Use as featured image',
				'insert_into_item'      => 'Insert into item',
				'uploaded_to_this_item' => 'Uploaded to this item',
				'items_list'            => 'Items list',
				'items_list_navigation' => 'Items list navigation',
				'filter_items_list'     => 'Filter items list',
			);

		}

		protected function post_type_setup_args() {
			//NOTE: overwrite in post type
		}

		protected function activate_post_type() {
			$this->loader->add_action( 'init', $this, 'register_custom_post_type', 0 );
			$this->loader->add_action( 'init', $this, 'register_custom_post_taxonomy', 0 );
			// die anderen sachen

			$this->loader->add_action( 'pre_get_posts', $this, 'pre_get_posts', 10 );

			if( ! current_theme_supports( 'marlon-breadcrumbs' ) ) {
				$this->unsupportet_by_theme();
			}

			return $this->post_type_admin();
		}

		protected function post_type_admin() {
			if( ! is_admin() ) {
				return;
			}
			$this->loader->add_action( 'add_meta_boxes', $this, 'metabox_add' );
			$this->loader->add_action( 'save_post', $this, 'metabox_save', 10, 2 );
			$this->loader->add_action( 'admin_enqueue_scripts', $this, 'post_type_admin_scripts', 10, 1 );

			$this->loader->add_filter( 'manage_edit-' . $this->get_setting( 'slug' ) . '_columns', $this, 'custom_columns_head', 10 );
			$this->loader->add_action( 'manage_' . $this->get_setting( 'slug' ) . '_posts_custom_column', $this, 'custom_columns_content', 10, 2 );
		}

		public function post_type_admin_scripts( $hook ) {
			global $typenow;
			if( $this->get_setting( 'slug' ) === $typenow ) {
				wp_enqueue_media();
				wp_register_script( 'meta-box-media', plugins_url( '../../admin/media.js' , __FILE__ ), array( 'jquery' ) );
				wp_localize_script( 'meta-box-media', 'meta_image',
					array(
						'title' => 'Choose or Upload Media',
						'button' => 'Use this media',
					)
				);
				wp_enqueue_script( 'meta-box-media' );
			}
		}

		protected function unsupportet_by_theme() {
			//NOTE: overwrite in post type
		}

		public function get_settings() {
			return $this->settings;
		}

		public function get_setting( $setting, $before = '', $after = '' ) {
			return $before . $this->settings[$setting] . $after;
		}

		public function get_labels() {
			return $this->labels;
		}

		public function set_label( $label, $value ) {
			$this->labels[$label] = $value;
			return $label . ': ' . $this->labels[$label];
		}

		public function get_args() {
			return $this->args;
		}

		public function set_arg( $arg, $value ) {
			$this->args[$arg] = $value;
		}

		public function register_custom_post_type() {
			register_post_type( $this->get_setting( 'slug' ), $this->get_args() );
		}

		public function register_custom_post_taxonomy() {
			//NOTE: overwrite in post type
		}

		public function pre_get_posts( &$query ) {
			if( ! function_exists( 'pll_the_languages' ) || is_admin() ) {
				return;
			}
			if( ! $this->get_setting( 'sync_languages' ) ){
				return;
			}

			if( $query->is_main_query() and ( is_archive( $this->get_setting( 'slug' ) ) ) ) {
				$query->set( 'lang', '' );

				if( is_array( $query->get( 'tax_query' ) ) ) {
					$tax_query = $query->get( 'tax_query' );

					foreach ( $tax_query as $i => $row ) {
						if( 'language' === $row['taxonomy'] ) {
							unset( $tax_query[ $i ] );
						}
					}

					$query->set( 'tax_query', $tax_query );
					$query = new WP_Query( $query->query_vars );
				}
			}
		}

		public function metabox_add() {
			if( empty( $this->custom_fields ) ) {
				return;
			}
			add_meta_box(
				$this->get_setting( 'slug' ) . '-meta-box',
				$this->get_setting( 'singular_label' ) . ' Detail',
				array( $this, 'metabox_display' ),
				array( $this->get_setting( 'slug' ) ),
				'normal',
				'high'
			);
		}

		public function metabox_display( $post ) {
			if( empty( $this->custom_fields ) ) {
				return;
			}
			wp_nonce_field( basename( __FILE__ ), $this->get_setting( 'slug' ) . '-nonce' );
			?>

			<div class="marlon-inside marlon-metabox">
				<table class="marlon-metabox-table">

				<?php
					foreach( $this->custom_fields as $key => $value ) {
						$this->custom_field_display( $value, $post );
					}
				?>

				</table>
			</div>

			<?php
		}

		public function metabox_save( $post_id, $post ) {
			if( empty( $this->custom_fields ) ) {
				return;
			}
			if( ! isset( $_POST[$this->get_setting( 'slug' ) . '-nonce'] ) || ! wp_verify_nonce( $_POST[$this->get_setting( 'slug' ) . '-nonce'], basename( __FILE__ ) ) ) {
				return;
			}
			if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			if( is_multisite() && ms_is_switched() ) {
				return $post_id;
			}
			if( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
			foreach( $this->custom_fields as $key => $value ) {
				$this->custom_field_save( $value, $post );
			}
		}

		protected function custom_field_display( $value, $post ) {
			switch( $value['type'] ) {
				case 'single_text':
					return $this->custom_field_display_single_text( $value, $post );
					break;
				case 'text':
					return $this->custom_field_display_text( $value, $post );
					break;
				case 'media':
					return $this->custom_field_display_media( $value, $post );
					break;
				case 'copy_media':
					return $this->custom_field_display_copy_media( $value, $post );
					break;
			}
		}

		protected function custom_field_display_single_text( $value, $post ) {
			$content = get_post_meta( $post->ID, $this->get_setting( 'slug' ) . '-' . $value['slug'], true );
			?>
			<tr>
				<td style="width:25%;"><label for="form-<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>"><?php echo $value['title']; ?></label></td>
				<td style="width:75%;">
					<input type="text" size="80" id="form-<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>" name="<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>" value="<?php echo $content; ?>" />
					<?php if( isset( $value['desc'] ) ) : ?><p class="description"><?php echo $value['desc']; ?></p><?php endif; ?>
				</td>
			</tr>
			<?php
		}

		protected function custom_field_display_text( $value, $post ) {
			$content = get_post_meta( $post->ID, $this->get_setting( 'slug' ) . '-' . $value['slug'], true );
			?>
			<tr>
				<td style="width:25%;"><label for="form-<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>"><?php echo $value['title']; ?></label></td>
				<td style="width:75%;">
					<textarea style="width: 100%;" rows="5" autocomplete="off" cols="40" id="form-<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>" name="<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>"><?php echo $content; ?></textarea>
					<?php if( isset( $value['desc'] ) ) : ?><p class="description"><?php echo $value['desc']; ?></p><?php endif; ?>
				</td>
			</tr>
			<?php
		}

		protected function custom_field_display_media( $value, $post ) {
			$content = get_post_meta( $post->ID, $this->get_setting( 'slug' ) . '-' . $value['slug'], true );
			?>
			<tr>
				<td style="width:25%;"><label for="form-<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>"><?php echo $value['title']; ?></label></td>
				<td style="width:75%;">
					<?php if( $content ) : ?>
					<?php
						$image_id      = attachment_url_to_postid( $content );
						$thumbnail_url = wp_get_attachment_image_src( $image_id, 'thumbnail' )[0];
					?>
					<img src="<?php echo $thumbnail_url; ?>">
					<?php endif; ?>
					<input type="url" size="80" id="form-<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>" name="<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>" value="<?php echo $content; ?>" />
					<button type="button" class="button" id="<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>-btn" data-media-uploader-target="#form-<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>">Insert Media</button>
					<?php if( isset( $value['desc'] ) ) : ?><p class="description"><?php echo $value['desc']; ?></p><?php endif; ?>
				</td>
			</tr>
			<?php
		}

		protected function custom_field_display_copy_media( $value, $post ) {
			$content = get_post_meta( $post->ID, $this->get_setting( 'slug' ) . '-' . $value['slug'], true );
			?>
			<tr>
				<td style="width:25%;"><label for="form-<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>"><?php echo $value['title']; ?></label></td>
				<td style="width:75%;">
					<?php if( $content ) : ?>
					<?php
						$image_id      = attachment_url_to_postid( $content );
						$thumbnail_url = wp_get_attachment_image_src( $image_id, 'thumbnail' )[0];
					?>
					<img src="<?php echo $thumbnail_url; ?>">
					<?php endif; ?>
					<input type="url" size="80" id="form-<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>-copy" name="<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>-copy" value="" />
					<p class="description">URL of Image on third party site<br>&nbsp;</p>
					<input type="url" size="80" id="form-<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>" name="<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>" value="<?php echo $content; ?>" />
					<button type="button" class="button" id="<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>-btn" data-media-uploader-target="#form-<?php echo $this->get_setting( 'slug' ) . '-' . $value['slug']; ?>">Insert Media</button>
					<?php if( isset( $value['desc'] ) ) : ?><p class="description"><?php echo $value['desc']; ?></p><?php endif; ?>
				</td>
			</tr>
			<?php
		}

		protected function custom_field_save( $value, $post ) {
			switch( $value['type'] ) {
				case 'single_text':
					return $this->custom_field_save_single_text( $value, $post );
					break;
				case 'text':
					return $this->custom_field_save_text( $value, $post );
					break;
				case 'media':
					return $this->custom_field_save_media( $value, $post );
					break;
				case 'copy_media':
					return $this->custom_field_save_copy_media( $value, $post );
					break;
			}
		}

		protected function custom_field_save_single_text( $value, $post ) {
			if( isset( $_POST[$this->get_setting( 'slug' ) . '-' . $value['slug']] ) ) {
				update_post_meta( $post->ID, $this->get_setting( 'slug' ) . '-' . $value['slug'], sanitize_text_field( $_POST[$this->get_setting( 'slug' ) . '-' . $value['slug']] ) );
			} else {
				delete_post_meta( $post->ID, $this->get_setting( 'slug' ) . '-' . $value['slug'] );
			}
		}

		protected function custom_field_save_text( $value, $post ) {
			if( isset( $_POST[$this->get_setting( 'slug' ) . '-' . $value['slug']] ) ) {
				update_post_meta( $post->ID, $this->get_setting( 'slug' ) . '-' . $value['slug'], sanitize_text_field( $_POST[$this->get_setting( 'slug' ) . '-' . $value['slug']] ) );
			} else {
				delete_post_meta( $post->ID, $this->get_setting( 'slug' ) . '-' . $value['slug'] );
			}
		}

		protected function custom_field_save_media( $value, $post ) {
			if( isset( $_POST[$this->get_setting( 'slug' ) . '-' . $value['slug']] ) ) {
				update_post_meta( $post->ID, $this->get_setting( 'slug' ) . '-' . $value['slug'], sanitize_text_field( $_POST[$this->get_setting( 'slug' ) . '-' . $value['slug']] ) );
			} else {
				delete_post_meta( $post->ID, $this->get_setting( 'slug' ) . '-' . $value['slug'] );
			}
		}

		protected function custom_field_save_copy_media( $value, $post ) {
			if( isset( $_POST[$this->get_setting( 'slug' ) . '-' . $value['slug'] . '-copy'] ) && $_POST[$this->get_setting( 'slug' ) . '-' . $value['slug'] . '-copy'] !== '' ) {
				$external = sanitize_text_field( $_POST[$this->get_setting( 'slug' ) . '-' . $value['slug'] . '-copy'] );
				$internal = media_sideload_image( $external, $post->ID, null, 'src' );
				update_post_meta( $post->ID, $this->get_setting( 'slug' ) . '-' . $value['slug'], $internal );
			} else {
				if( isset( $_POST[$this->get_setting( 'slug' ) . '-' . $value['slug']] ) ) {
					update_post_meta( $post->ID, $this->get_setting( 'slug' ) . '-' . $value['slug'], sanitize_text_field( $_POST[$this->get_setting( 'slug' ) . '-' . $value['slug']] ) );
				} else {
					delete_post_meta( $post->ID, $this->get_setting( 'slug' ) . '-' . $value['slug'] );
				}
			}
		}

		public function custom_columns_head( $defaults ) {

			global $typenow;
			if( $this->get_setting( 'slug' ) === $typenow ) {

				if( empty( $this->custom_fields ) ) {
					return;
				}

				$new = array();
				foreach( $defaults as $key => $title ) {
					$new[$key] = $title;
					if( 'title' === $key ) {
						foreach( $this->custom_fields as $key => $value ) {
							if( $value['admin_col'] ) {
								$new[$this->get_setting( 'slug' ) . '-' . $value['slug']] = $value['title'];
							}
						}
					}
				}
				$defaults = $new;

			}

			return $defaults;

		}

		public function custom_columns_content( $column_name, $post_id ) {

			global $typenow;
			if( $this->get_setting( 'slug' ) === $typenow ) {

				foreach( $this->custom_fields as $key => $value ) {
					if( $this->get_setting( 'slug' ) . '-' . $value['slug'] === $column_name ) {
						$this->custom_column_content( $value, $post_id );
					}
				}
			}

		}

		protected function custom_column_content( $value, $post_id ) {
			switch( $value['type'] ) {
				case 'single_text':
					return $this->custom_column_content_single_text( $value, $post_id );
					break;
				case 'text':
					return $this->custom_column_content_text( $value, $post_id );
					break;
				case 'media':
					return $this->custom_column_content_media( $value, $post_id );
					break;
				case 'copy_media':
					return $this->custom_column_content_copy_media( $value, $post_id );
					break;
			}
		}

		protected function custom_column_content_single_text( $value, $post_id ) {
			echo get_post_meta( $post_id, $this->get_setting( 'slug' ) . '-' . $value['slug'], true );
		}

		protected function custom_column_content_text( $value, $post_id ) {
			echo get_post_meta( $post_id, $this->get_setting( 'slug' ) . '-' . $value['slug'], true );
		}

		protected function custom_column_content_media( $value, $post_id ) {
			$image = get_post_meta( $post_id, $this->get_setting( 'slug' ) . '-' . $value['slug'], true );
			if( $image ) {
				$image_id      = attachment_url_to_postid( $image );
				$thumbnail_url = wp_get_attachment_image_src( $image_id, 'thumbnail' )[0];
				echo '<img src="' . $thumbnail_url . '">';
			}
		}

		protected function custom_column_content_copy_media( $value, $post_id ) {
			$image = get_post_meta( $post_id, $this->get_setting( 'slug' ) . '-' . $value['slug'], true );
			if( $image ) {
				$image_id      = attachment_url_to_postid( $image );
				$thumbnail_url = wp_get_attachment_image_src( $image_id, 'thumbnail' )[0];
				echo '<img src="' . $thumbnail_url . '">';
			}
		}

	}
}
