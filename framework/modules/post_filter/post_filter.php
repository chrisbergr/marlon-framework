<?php

if ( ! class_exists( 'Post_Filter' ) ) {
	class Post_Filter extends Marlon_Module {

		protected function init_module() {
			$this->loader->add_action( 'themeberger_after_header', $this, 'current_filter', 60 );
			$this->loader->add_action( 'themeberger_after_header', $this, 'available_filter', 61 );
			$this->loader->add_action( 'pre_get_posts', $this, 'filter_posts', 9 );
		}

		private function count_posts_by_tag_combination( $tags, $category = false ) {
			$tags_args = array( 'relation' => 'AND' );
			foreach( $tags as $tag ) {
				$tags_args[] = array(
					'taxonomy' => 'post_tag',
					'field'    => 'slug',
					'terms'    => $tag,
				);
			}
			$args = array(
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'tax_query'      => $tags_args,
			);
			if( $category ){
				$args['category_name'] = $category;
			}
			$posts = get_posts( $args );
			$count = count( $posts );
			return $count;
		}

		public function current_filter() {

			if( ! is_home() && ! is_category() ) {
				return;
			}

			$current_filter = array();

			if( isset( $_GET['filter'] ) && '' !== $_GET['filter'] ) {
				$current_filter = explode( '--', esc_attr( $_GET['filter'] ) );
			}

			$base = get_permalink( get_option( 'page_for_posts' ) );

			if( is_category() ){
				$category = get_category( get_query_var( 'cat' ) );
				$base = get_category_link( $category->cat_ID );
			}

			/**/

			if( 0 < count( $current_filter ) ) :
				$available_filter = array();
				foreach( $current_filter as $filter ) {
					$new_filter = $current_filter;
					$term = get_term_by( 'slug', $filter, 'post_tag' );
					$tag_name = $term->name;

					$new_filter = array_diff( $new_filter, array( $filter ) );

					$url = esc_url( $base . '?filter=' . implode( '--', $new_filter ) );
					$filter_arg = '<a href="' . $url . '" class="remove-filter"><strong>' . $tag_name . '</strong></a>';
					$available_filter[] = $filter_arg;
				}
				if( 0 < count( $available_filter ) ) :
					?>
					<section class="taxonomy-filter">
						<div class="taxonomy-filter-inner">
							<p><?php _e( 'Current Filter', 'marlon' ); ?>: <?php echo implode( ', ', $available_filter ); ?></p>
						</div>
					</section>
					<?php
				endif;
			endif;

		}

		public function available_filter() {

			if( ! is_home() && ! is_category() ) {
				return;
			}

			$current_filter = array();
			$cat_name = false;

			if( isset( $_GET['filter'] ) && '' !== $_GET['filter'] ) {
				$current_filter = explode( '--', esc_attr( $_GET['filter'] ) );
			}

			$base = get_permalink( get_option( 'page_for_posts' ) );
			$query = array(
				'posts_per_page' => -1,
			);

			if( is_category() ){
				$category = get_category( get_query_var( 'cat' ) );
				$base = get_category_link( $category->cat_ID );
				$cat_name = $category->category_nicename;
				$query['category_name'] = $cat_name;
			}

			if( 0 < count( $current_filter ) ) {
				$filter_ids = array();
				foreach( $current_filter as $current_filter_slug ) {
					$tag = get_term_by( 'slug', $current_filter_slug, 'post_tag' );
					$filter_ids[] = $tag->term_id;
				}
				$query['tag__and'] = $filter_ids;
			}

			$custom_query = new WP_Query( $query );
			if ( $custom_query->have_posts() ) :
				while ( $custom_query->have_posts() ) : $custom_query->the_post();
					$posttags = get_the_tags();
					if ( $posttags ) {
						foreach( $posttags as $tag ) {
							$all_tags[] = $tag->term_id;
						}
					}
				endwhile;
			endif;

			$tags_arr = array_unique( $all_tags );

			/**/

			if( 0 < count( $tags_arr ) ) :
				$available_filter = array();
				foreach( $tags_arr as $tag_id ){
					$tag = get_tag( $tag_id );
					$tag_name = esc_attr( $tag->name );
					$tag_slug = esc_attr( $tag->slug );
					if( ! in_array( $tag_slug, $current_filter) ) {
						$new_filter = $current_filter;
						$new_filter[] = $tag_slug;

						$filter_results = $this->count_posts_by_tag_combination( $new_filter, $cat_name );

						$filter_arg = '<a href="' . esc_url( $base . '?filter=' . implode( '--', $new_filter ) ) . '" class="add-filter"><strong>' . $tag_name . '</strong></a><sup>' . $filter_results . '</sup>';
						$available_filter[] = $filter_arg;
					}
				}
				if( 0 < count( $available_filter ) ) :
					?>
					<section class="taxonomy-filter">
						<div class="taxonomy-filter-inner">
							<p><?php _e( 'Available Filter', 'marlon' ); ?>: <?php echo implode( ', ', $available_filter ); ?></p>
						</div>
					</section>
					<?php
				endif;
			endif;

		}

		public function filter_posts( $query ) {

			if( ! is_admin() && $query->is_main_query() ) {

				if( ! is_home() && ! is_category() ) {
					return;
				}

				$current_filter = array();

				if( isset( $_GET['filter'] ) && '' !== $_GET['filter'] ) {
					$current_filter = explode( '--', esc_attr( $_GET['filter'] ) );
				}

				if( 0 < count( $current_filter ) ) {
					$filter_ids = array();
					foreach( $current_filter as $current_filter_slug ) {
						$tag = get_term_by( 'slug', $current_filter_slug, 'post_tag' );
						$filter_ids[] = $tag->term_id;
					}
					$query->set( 'tag__and', $filter_ids );
					$query->posts_per_page = 2;

					return $query;
				}

			}

		}

	}
}
