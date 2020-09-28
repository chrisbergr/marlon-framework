<?php

if ( ! class_exists( 'Post_Type_Pin' ) ) {
	class Post_Type_Pin extends Marlon_Post_Type {

		protected function init_post_type() {

			$this->settings = array(
				'name'           => 'Pins',
				'plural_label'   => 'Pins',
				'singular_label' => 'Pin',
				'slug'           => 'pins',
				'sync_languages' => true,
			);

			$this->custom_fields = array(
				'media' => array(
					'title'     => 'Media',
					'desc'      => 'Insert your media.',
					'slug'      => 'media',
					//'type'      => 'media',
					'type'      => 'copy_media',
					'admin_col' => true,
				),
				'bookmark' => array(
					'title'     => 'Bookmark',
					'desc'      => 'Insert URL of your target.',
					'slug'      => 'bookmark',
					'type'      => 'single_text',
					'admin_col' => false,
				),
				'description' => array(
					'title'     => 'Description',
					'desc'      => 'Write the description of your pin.',
					'slug'      => 'description',
					'type'      => 'text',
					'admin_col' => true,
				),
			);

		}

		protected function post_type_setup_args() {

			$this->args = array(
				'label'                => $this->get_setting( 'name' ),
				'labels'               => $this->get_labels(),
				'description'          => '',
				'public'               => true,
				'hierarchical'         => false,
				'exclude_from_search'  => false,
				'publicly_queryable'   => true,
				'show_ui'              => true,
				'show_in_menu'         => true,
				'show_in_nav_menus'    => true,
				'show_in_admin_bar'    => true,
				'show_in_rest'         => true,
				'menu_position'        => 39,
				'menu_icon'            => null,
				'capability_type'      => 'post',
				'supports'             => array( 'title' ),
				//'supports'             => array( 'title', 'editor', 'author' ),
				'has_archive'          => true,
				'rewrite'              => array(
											'slug' => $this->get_setting( 'slug' ),
											'with_front' => true,
											'feeds' => true,
											'pages' => true,
										),
				'can_export'           => true,
			);

		}

		public function register_custom_post_taxonomy() {

			$labels = array(
				'name'                       => $this->get_setting( 'singular_label', '', ' Tags' ),
				'singular_name'              => $this->get_setting( 'singular_label', '', ' Tag' ),
				'search_items'               => $this->get_setting( 'singular_label', 'Search ', ' Tags' ),
				'popular_items'              => $this->get_setting( 'singular_label', 'Popular ', ' Tags' ),
				'all_items'                  => $this->get_setting( 'singular_label', 'All ', ' Tags' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'                  => $this->get_setting( 'singular_label', 'Edit ', ' Tag' ),
				'update_item'                => $this->get_setting( 'singular_label', 'Update ', ' Tag' ),
				'add_new_item'               => $this->get_setting( 'singular_label', 'Add new ', ' Tag' ),
				'new_item_name'              => $this->get_setting( 'singular_label', 'Name of new ', ' Tag' ),
				'separate_items_with_commas' => $this->get_setting( 'singular_label', '', ' Tag Seperator' ),
				'add_or_remove_items'        => $this->get_setting( 'singular_label', 'Add or Remove ', ' Tags' ),
				'choose_from_most_used'      => $this->get_setting( 'singular_label', 'Choose from most used ', ' Tags' ),
				'menu_name'                  => $this->get_setting( 'singular_label', '', ' Tags' ),
			);

			$args = array(
				'hierarchical'          => false,
				'labels'                => $labels,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'show_in_rest'          => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var'             => true,
				'rewrite'               => array( 'textdomain' => $this->get_setting( 'slug' ) . '/tags', 'with_front' => true ),
			);

			register_taxonomy( $this->get_setting( 'slug' ) . '-tags', $this->get_setting( 'slug' ), $args );

		}

		protected function unsupportet_by_theme() {
			$this->loader->add_filter( 'the_content', $this, 'filter_the_content', 1 );
			$this->loader->add_filter( 'the_excerpt', $this, 'filter_the_excerpt', 1 );
		}

		protected function custom_post_type_content( $post_id ) {
			$image       = get_post_meta( $post_id, $this->get_setting( 'slug' ) . '-media', true );
			$bookmark    = get_post_meta( $post_id, $this->get_setting( 'slug' ) . '-bookmark', true );
			$description = get_post_meta( $post_id, $this->get_setting( 'slug' ) . '-description', true );

			$out = '';
			$out .= '<div class="marlon-post-type-pin">';

			$out .= '<p><a href="' . $bookmark . '" target="_blank"><img src="' . $image . '"></a></p>';
			$out .= '<p>' . $description . '</p>';

			$out .= '</div>';

			return $out;
		}

		public function filter_the_content( $content ) {
			//if ( is_singular() && in_the_loop() && is_main_query() ) {
			if ( is_singular() ) {
				if ( $this->get_setting( 'slug' ) === get_post_type() ) {
					$content = $this->custom_post_type_content( get_the_id() ) . $content;
				}
			}

			return $content;
		}

		public function filter_the_excerpt( $content ) {
			if ( ! is_singular() ) {
				if ( $this->get_setting( 'slug' ) === get_post_type() ) {
					$content = $this->custom_post_type_content( get_the_id() ) . $content;
				}
			}
			return $content;
		}

	}
}
