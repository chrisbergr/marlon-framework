<?php

if ( ! class_exists( 'Comments_Merger' ) ) {
	class Comments_Merger extends Marlon_Module {

		protected function init_module() {
			if( $this->is_third_party_active( 'polylang/polylang.php' ) ) {
				$this->loader->add_filter( 'comments_array', $this, 'merge_comments', 100, 2 );
				$this->loader->add_filter( 'get_comments_number', $this, 'merge_comment_count', 100, 2 );
				$this->loader->add_action( 'wp', $this, 'polylang_remove_comments_filter' );
			}
		}

		private static function sort_merged_comments( $a, $b ) {
			return $a->comment_ID - $b->comment_ID;
		}

		public function merge_comments( $comments, $post_ID ) {
			global $polylang;
			$translationIds = PLL()->model->post->get_translations( $post_ID );
			foreach( $translationIds as $key => $translationID ) {
				if( $translationID !== $post_ID ) {
					$translatedPostComments = get_comments(
						array(
							'post_id' => $translationID,
							'status' => 'approve',
							'order' => 'ASC',
						)
					);
					if( $translatedPostComments ) {
						$comments = array_merge( $comments, $translatedPostComments );
					}
				}
			}
			if( count( $translationIds ) > 1 ) {
				usort( $comments, array( $this, 'sort_merged_comments' ) );
			}
			return $comments;
		}

		public function merge_comment_count( $count, $post_ID ) {
			if( ! is_admin() ) {
				global $polylang;
				$translationIds = PLL()->model->post->get_translations( $post_ID );
				foreach( $translationIds as $key => $translationID ) {
					if( $translationID !== $post_ID ) {
						$translatedPost = get_post( $translationID );
						if ( $translatedPost ) {
							$count = $count + $translatedPost->comment_count;
						}
					}
				}
			}
			return $count;
		}

		public function polylang_remove_comments_filter() {
			global $polylang;
			remove_filter( 'comments_clauses', array( &$polylang->filters, 'comments_clauses' ) );
		}

	}
}
