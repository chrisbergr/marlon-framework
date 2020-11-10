<?php

if ( ! class_exists( 'Comment_Utilities' ) ) {
	class Comment_Utilities extends Marlon_Module {

		private $comment_id = 0;
		private $comment = null;

		protected function init_module() {}

		public function setup_module( $comment ) {
			if ( ! $comment ) {
				return;
			}
			if ( is_a( $comment, 'WP_Comment' ) ) {
				$this->comment_id = absint( $comment->comment_ID );
				$this->comment    = $comment;
			} else {
				$this->comment_id = absint( $comment );
				$this->comment    = get_comment( $this->comment_id );
			}
		}

		private function utility_get( $data, $comment_id = false ) {
			//if ( ! $post_id ) {
			//	$post_id = get_the_ID();
			//}
			//$this->setup_module( $post_id );
			return $data;
		}

		private function utility_print( $data, $echo = true ) {
			if ( ! $echo ) {
				return $data;
			}
			echo $data;
		}

		public function build_comment_author_vcard( $args = array() ) {

			$args = wp_parse_args(
				$args,
				array(
					'name'      => 'Unknown',
					'url'       => null,
					'image_src' => null,
					'image'     => null,
					'class'     => null,
				)
			);

			$author_name_tpl = '<span class="name p-name fn">%1$s</span>';
			$author_name_tpl = apply_filters( 'marlon_comment_author_vcard_name', $author_name_tpl );
			$author_name = sprintf( $author_name_tpl, $args['name'] );

			$author_avatar = '';
			if ( $args['image_src'] ) {
				$author_image_tpl = '<img class="photo avatar" src="%1$s" alt="%2$s">';
				$author_image_tpl = apply_filters( 'marlon_comment_author_vcard_image', $author_image_tpl );
				$author_avatar = sprintf( $author_image_tpl, $args['image_src'], $args['name'] );
			}
			if ( $args['image'] ) {
				$author_avatar = $args['image'];
			}

			$author_str = $author_avatar . $author_name;

			if ( $args['url'] ) {
				$author_link_tpl = '<a class="url u-url" href="%1$s">%2$s</a>';
				$author_link_tpl = apply_filters( 'marlon_comment_author_vcard_link', $author_link_tpl );
				$author_str = sprintf( $author_link_tpl, $args['url'], $author_str );
			}

			$author_class = '';
			if ( $args['class'] ) {
				$author_class = ' ' . $args['class'];
			}
			$author_class = apply_filters( 'marlon_comment_author_vcard_class', $author_class );

			$author_tpl = '<span class="comment-author themeberger-comment-author vcard%1$s">%2$s</span>';
			$author_tpl = apply_filters( 'marlon_comment_author_vcard_container', $author_tpl );
			$author = sprintf( $author_tpl, $author_class, $author_str );

			return $author;

		}

		private function get_comment_author_vcard( $args = '', $comment_id = false ) {

			$this->setup_module( $comment_id );

			$args = wp_parse_args(
				$args,
				array(
					'before' => '',
					'after'  => '',
				)
			);

			$author = $this->build_comment_author_vcard(
				[
					//'name'      => get_comment_author( $this->comment ),
					'name'      => esc_html( get_comment_author( $this->comment_id ) ),
					'url'       => esc_url( get_comment_author_url( $this->comment_id ) ),
					'image_src' => get_avatar_url( $this->comment, array( 'size' => 50 ) ),
					'image'     => null,
					'class'     => null,
				]
			);

			$author = apply_filters( 'marlon_comment_author_vcard', $author, $this->comment );
			$author = $args['before'] . $author . $args['after'];

			return $author;

		}
		public function get_the_comment_author_vcard( $before = '', $after = '', $comment_id = false ) {
			return $this->utility_get(
				$this->get_comment_author_vcard(
					array(
						'before' => $before,
						'after'  => $after,
					),
					$comment_id
				)
			);
		}
		public function the_comment_author_vcard( $before = '', $after = '', $comment_id = false, $echo = true ) {
			return $this->utility_print( $this->get_the_comment_author_vcard( $before, $after, $comment_id ), $echo );
		}

		private function get_comment_date( $args = '', $comment_id = false ) {

			$this->setup_module( $comment_id );

			$args = wp_parse_args(
				$args,
				array(
					'before'         => '',
					'after'          => '',
					'class'          => null,
					'human_readable' => false,
				)
			);

			$date_string = '<time class="comment-date published%3$s" datetime="%1$s">%2$s</time>';
			$date_string = apply_filters( 'marlon_comment_date_html', $date_string );

			$date_class = '';
			if ( $args['class'] ) {
				$date_class = ' ' . $args['class'];
			}
			$date_class = apply_filters( 'marlon_comment_date_class', $date_class );

			if ( $args['human_readable'] ) {
				$comment_date = sprintf(
					$date_string,
					esc_attr( get_comment_date( 'c', $this->comment ) ),
					sprintf(
						/* translators: %s = human-readable time difference */
						esc_html__( '%s ago', 'marlon' ),
						human_time_diff( get_comment_date( 'U', $this->comment ), current_time( 'timestamp' ) )
					),
					$date_class
				);
			} else {
				$comment_date = sprintf(
					$date_string,
					esc_attr( get_comment_date( 'c', $this->comment ) ),
					esc_html( get_comment_date( '', $this->comment ) ),
					$date_class
				);
			}

			$comment_date = apply_filters( 'marlon_comment_date', $comment_date, $this->comment );
			$comment_date = $args['before'] . $comment_date . $args['after'];

			return $comment_date;

		}
		public function get_the_comment_date( $before = '', $after = '', $class = '', $human = false, $comment_id = false ) {
			return $this->utility_get(
				$this->get_comment_date(
					array(
						'before'         => $before,
						'after'          => $after,
						'class'          => $class,
						'human_readable' => $human,
					),
					$comment_id
				)
			);
		}
		public function the_comment_date( $before = '', $after = '', $class = '', $human = false, $comment_id = false, $echo = true ) {
			return $this->utility_print( $this->get_the_comment_date( $before, $after, $class, $human, $comment_id ), $echo );
		}

		private function get_commentlink_date( $args = '', $comment_id = false ) {

			$this->setup_module( $comment_id );

			$args = wp_parse_args(
				$args,
				array(
					'before'         => '',
					'after'          => '',
					'human_readable' => false,
				)
			);

			$permalink_tpl = '<a href="%1$s" class="uid u-uid url u-url">%2$s</a>';
			$permalink_tpl = apply_filters( 'marlon_commentlink_date_html', $permalink_tpl );

			$permalink = sprintf(
				$permalink_tpl,
				esc_url( get_comment_link( $this->comment ) ),
				$this->get_comment_date( array( 'human_readable' => $args['human_readable'] ), $comment_id )
			);

			$permalink = apply_filters( 'marlon_commentlink_date', $permalink, $this->comment );
			$permalink = $args['before'] . $permalink . $args['after'];

			return $permalink;

		}
		public function get_the_commentlink_date( $before = '', $after = '', $human = false, $comment_id = false ) {
			return $this->utility_get(
				$this->get_commentlink_date(
					array(
						'before'         => $before,
						'after'          => $after,
						'human_readable' => $human,
					),
					$comment_id
				)
			);
		}
		public function the_commentlink_date( $before = '', $after = '', $human = false, $comment_id = false, $echo = true ) {
			return $this->utility_print( $this->get_the_commentlink_date( $before, $after, $human, $comment_id ), $echo );
		}

	}
}
