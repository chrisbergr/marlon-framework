<?php

if ( ! class_exists( 'Post_Utilities' ) ) {
	class Post_Utilities extends Marlon_Module {

		private $post_id = 0;
		private $post = null;

		protected function init_module() {}

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

		private function utility_get( $data, $post_id = false ) {
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

		public function article_in_collection() {
			if ( ! is_single() ) {
				return $this->utility_print( 'itemprop="hasPart"' );
			}
		}

		private function get_shorturl( $args = '', $post_id = false ) {

			$this->setup_module( $post_id );

			$args = wp_parse_args(
				$args,
				array(
					'before' => '',
					'after'  => '',
				)
			);

			$shortlink_classes = array(
				'u-shorturl',
				'u-shortlink',
				'shortlink'
			);
			$shortlink_classes = apply_filters( 'marlon_shortlink_classes', $shortlink_classes );
			$shortlink_classes = implode( ' ', $shortlink_classes );

			$shortlink_string  = '<a href="%1$s" class="%2$s" rel="shortlink" type="text/html">%3$s</a>';
			$shortlink_full    = wp_get_shortlink( $this->post_id );
			$shortlink_display = preg_replace( '(^https?://)', '', $shortlink_full );

			$shortlink = sprintf(
				$shortlink_string,
				$shortlink_full,
				$shortlink_classes,
				$shortlink_display
			);

			$shortlink = apply_filters( 'marlon_shortlink', $shortlink, $this->post );
			$shortlink = $args['before'] . $shortlink . $args['after'];

			return $shortlink;

		}
		public function get_the_shorturl( $before = '', $after = '', $post_id = false ) {
			return $this->utility_get(
				$this->get_shorturl(
					array(
						'before' => $before,
						'after'  => $after,
					),
					$post_id
				)
			);
		}
		public function the_shorturl( $before = '', $after = '', $echo = true ) {
			return $this->utility_print( $this->get_the_shorturl( $before, $after, get_the_ID() ), $echo );
		}

		public function build_author_vcard( $args = array() ) {

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

			$author_name_tpl = '<span class="name p-name fn" itemprop="name">%1$s</span>';
			$author_name_tpl = apply_filters( 'marlon_author_vcard_name', $author_name_tpl );
			$author_name = sprintf( $author_name_tpl, $args['name'] );

			$author_avatar = '';
			if ( $args['image_src'] ) {
				$author_image_tpl = '<img class="photo u-photo avatar" itemprop="image" src="%1$s" alt="%2$s">';
				$author_image_tpl = apply_filters( 'marlon_author_vcard_image', $author_image_tpl );
				$author_avatar = sprintf( $author_image_tpl, $args['image_src'], $args['name'] );
			}
			if ( $args['image'] ) {
				$author_avatar = $args['image'];
			}

			$author_str = $author_avatar . $author_name;

			if ( $args['url'] ) {
				$author_link_tpl = '<a class="url u-url" itemprop="url" rel="author" href="%1$s">%2$s</a>';
				$author_link_tpl = apply_filters( 'marlon_author_vcard_link', $author_link_tpl );
				$author_str = sprintf( $author_link_tpl, $args['url'], $author_str );
			}

			$author_class = '';
			if ( $args['class'] ) {
				$author_class = ' ' . $args['class'];
			}
			$author_class = apply_filters( 'marlon_author_vcard_class', $author_class );

			$author_tpl = '<span class="marlon-author author p-author vcard hcard h-card%1$s" itemprop="author" itemscope itemtype="http://schema.org/Person">%2$s</span>';
			$author_tpl = apply_filters( 'marlon_author_vcard_container', $author_tpl );
			$author = sprintf( $author_tpl, $author_class, $author_str );

			return $author;

		}

		private function get_author_vcard( $args = '', $post_id = false ) {

			$this->setup_module( $post_id );

			$args = wp_parse_args(
				$args,
				array(
					'before' => '',
					'after'  => '',
				)
			);

			$author_id = get_post_field( 'post_author', $this->post_id );

			$avatar = get_avatar(
				$author_id,
				50,
				null,
				esc_html( get_the_author_meta( 'display_name', $author_id ) ),
				array(
					'class'      => 'u-photo',
					'extra_attr' => 'itemprop="image"',
				)
			);

			$url  = esc_url( get_author_posts_url( $author_id ) );
			$name = esc_html( get_the_author_meta( 'display_name', $author_id ) );

			$author = $this->build_author_vcard(
				[
					'name'      => $name,
					'url'       => $url,
					'image_src' => null,
					'image'     => $avatar,
					'class'     => null,
				]
			);

			$author = apply_filters( 'marlon_author_vcard', $author, $this->post );
			$author = $args['before'] . $author . $args['after'];

			return $author;

		}
		public function get_the_author_vcard( $before = '', $after = '', $post_id = false ) {
			return $this->utility_get(
				$this->get_author_vcard(
					array(
						'before' => $before,
						'after'  => $after,
					),
					$post_id
				)
			);
		}
		public function the_author_vcard( $before = '', $after = '', $echo = true ) {
			return $this->utility_print( $this->get_the_author_vcard( $before, $after, get_the_ID() ), $echo );
		}

		private function get_post_date( $args = '', $post_id = false ) {

			$this->setup_module( $post_id );

			$args = wp_parse_args(
				$args,
				array(
					'before'         => '',
					'after'          => '',
					'class'          => null,
					'human_readable' => false,
				)
			);

			if ( get_the_time( 'U', $this->post ) !== get_the_modified_time( 'U', $this->post ) ) {
				$date_string = '<time class="entry-date published dt-published%5$s" itemprop="datePublished" datetime="%1$s">%2$s</time><time class="updated dt-updated%5$s" itemprop="dateModified" datetime="%3$s">%4$s</time>';
				$date_string = apply_filters( 'marlon_post_date_updated_html', $date_string );
			} else {
				$date_string = '<time class="entry-date published updated dt-published dt-updated%5$s" itemprop="datePublished dateModified" datetime="%1$s">%2$s</time>';
				$date_string = apply_filters( 'marlon_post_date_html', $date_string );
			}

			$date_class = '';
			if ( $args['class'] ) {
				$date_class = ' ' . $args['class'];
			}
			$date_class = apply_filters( 'marlon_post_date_class', $date_class );

			if ( $args['human_readable'] ) {
				$post_date = sprintf(
					$date_string,
					esc_attr( get_the_date( 'c', $this->post ) ),
					sprintf(
						/* translators: %s = human-readable time difference */
						esc_html__( '%s ago', 'marlon' ),
						human_time_diff( get_the_time( 'U', $this->post ), current_time( 'timestamp' ) )
					),
					esc_attr( get_the_modified_date( 'c', $this->post ) ),
					sprintf(
						/* translators: %s = human-readable time difference */
						esc_html__( '%s ago', 'marlon' ),
						human_time_diff( get_the_modified_time( 'U', $this->post ), current_time( 'timestamp' ) )
					),
					$date_class
				);
			} else {
				$post_date = sprintf(
					$date_string,
					esc_attr( get_the_date( 'c', $this->post ) ),
					esc_html( get_the_date( '', $this->post ) ),
					esc_attr( get_the_modified_date( 'c', $this->post ) ),
					esc_html( get_the_modified_date( '', $this->post ) ),
					$date_class
				);
			}

			$post_date = apply_filters( 'marlon_post_date', $post_date, $this->post );
			$post_date = $args['before'] . $post_date . $args['after'];

			return $post_date;

		}
		public function get_the_post_date( $before = '', $after = '', $class = '', $human = false, $post_id = false ) {
			return $this->utility_get(
				$this->get_post_date(
					array(
						'before'         => $before,
						'after'          => $after,
						'class'          => $class,
						'human_readable' => $human,
					),
					$post_id
				)
			);
		}
		public function the_post_date( $before = '', $after = '', $class = '', $human = false, $echo = true ) {
			return $this->utility_print( $this->get_the_post_date( $before, $after, $class, $human, get_the_ID() ), $echo );
		}

		private function get_permalink_date( $args = '', $post_id = false ) {

			$this->setup_module( $post_id );

			$args = wp_parse_args(
				$args,
				array(
					'before'         => '',
					'after'          => '',
					'human_readable' => false,
				)
			);

			$permalink_title = wp_sprintf(
				/* translators: 1 = Post Title, 2 = Author Name */
				__( '%1$s by %2$s', 'marlon' ),
				get_the_title( $this->post ) ? get_the_title( $this->post ) : __( 'A post', 'marlon' ),
				get_the_author_meta( 'display_name', get_post_field( 'post_author', $this->post_id ) )
			);

			$permalink_tpl = '<a href="%1$s" class="uid u-uid url u-url" title="%2$s" rel="bookmark">%3$s</a>';
			$permalink_tpl = apply_filters( 'marlon_permalink_date_html', $permalink_tpl );

			$permalink = sprintf(
				$permalink_tpl,
				esc_url( get_permalink( $this->post ) ),
				$permalink_title,
				$this->get_post_date( array( 'human_readable' => $args['human_readable'] ) )
			);

			$permalink = apply_filters( 'marlon_permalink_date', $permalink, $this->post );
			$permalink = $args['before'] . $permalink . $args['after'];

			return $permalink;

		}
		public function get_the_permalink_date( $before = '', $after = '', $human = false, $post_id = false ) {
			return $this->utility_get(
				$this->get_permalink_date(
					array(
						'before'         => $before,
						'after'          => $after,
						'human_readable' => $human,
					),
					$post_id
				)
			);
		}
		public function the_permalink_date( $before = '', $after = '', $human = false, $echo = true ) {
			return $this->utility_print( $this->get_the_permalink_date( $before, $after, $human, get_the_ID() ), $echo );
		}

		private function get_first_quote_of_post( $args = '', $post_id = false ) {

			$this->setup_module( $post_id );

			$args = wp_parse_args(
				$args,
				array(
					'before' => '',
					'after'  => '',
					'class'  => '',
				)
			);

			$content = do_shortcode( apply_filters( 'the_content', $this->post->post_content ) );

			$pattern = '#<blockquote[^>]*>([^<]+|<(?!/?blockquote)[^>]*>|(?R))+</blockquote>#i';
			preg_match_all( $pattern, $content, $matches );

			if ( ! empty( $matches ) && ! empty( $matches[0] ) ) {
				$first_quote = $matches[0][0];
			}
			if ( empty( $first_quote ) ) {
				return 'EMPTY QUOTE!!';
			}

			$cite    = '';
			$pattern = '#<cite[^>]*>([\s\S]+?)</cite>#i';
			preg_match_all( $pattern, $first_quote, $matches );
			if ( ! empty( $matches ) && ! empty( $matches[0] ) ) {
				$cite = $matches[0][0];
				$cite = '<footer>' . $cite . '</footer>';
			}
			$first_quote = preg_replace( $pattern, '', $first_quote );
			$pattern     = '#<p>([\s\S]+?)</p>#i';
			preg_match_all( $pattern, $first_quote, $matches );
			$paragraphs = implode( '', $matches[0] );

			$quote_class = '';
			if ( $args['class'] ) {
				$quote_class = ' ' . $args['class'];
			}
			$quote_class = apply_filters( 'marlon_first_quote_of_post_class', $quote_class );

			$quote_tpl = '<blockquote class="marlon-quote%2$s">%1$s</blockquote>';
			$quote_tpl = apply_filters( 'marlon_first_quote_of_post_container', $quote_tpl );
			$quote = sprintf(
				$quote_tpl,
				$paragraphs . $cite,
				$quote_class
			);

			$quote = apply_filters( 'themeberger_first_quote_of_post', $quote, $this->post );
			$quote = $args['before'] . $quote . $args['after'];

			return $quote;

		}
		public function get_the_first_quote_of_post( $before = '', $after = '', $post_id = false ) {
			return $this->utility_get(
				$this->get_first_quote_of_post(
					array(
						'before' => $before,
						'after'  => $after,
					),
					$post_id
				)
			);
		}
		public function the_first_quote_of_post( $before = '', $after = '', $echo = true ) {
			return $this->utility_print( $this->get_the_first_quote_of_post( $before, $after, get_the_ID() ), $echo );
		}

		private function get_content_without_first_quote( $post_id = false ) {

			$this->setup_module( $post_id );

			$content = $this->post->post_content;

			$content = do_shortcode( apply_filters( 'the_content', $content ) );
			$pattern = '#<blockquote[^>]*>([^<]+|<(?!/?blockquote)[^>]*>|(?R))+</blockquote>#i';
			$content = preg_replace( $pattern, '', $content );
			$content = str_replace( '<p></p>', '', $content );

			return $content;

		}
		public function get_the_content_without_first_quote( $post_id = false ) {
			return $this->utility_get(
				$this->get_content_without_first_quote( $post_id )
			);
		}
		public function the_content_without_first_quote( $echo = true ) {
			return $this->utility_print( $this->get_the_content_without_first_quote( get_the_ID() ), $echo );
		}

		private function get_first_image_of_post( $args = '', $post_id = false ) {

			$this->setup_module( $post_id );

			$args = wp_parse_args(
				$args,
				array(
					'before' => '',
					'after'  => '',
				)
			);

			$content = do_shortcode( apply_filters( 'the_content', $this->post->post_content ) );
			$pattern = '/<figure[^>]*>(.)*<\/figure[^>]*>/i';
			preg_match_all( $pattern, $content, $matches );

			if ( isset( $matches ) && isset( $matches[0] ) && isset( $matches[0][0] ) ) {
				$first_image = $matches[0][0];
				$pattern     = '/data-src\=\"(.)*\"/U';
				preg_match_all( $pattern, $first_image, $source );
				if ( isset( $source ) && isset( $source[0] ) && isset( $source[0][0] ) ) {
					$src         = str_replace( '"', '', $source[0][0] );
					$src         = str_replace( 'data-src=', '', $src );
					$first_image = $first_image . '<span class="marlon-data" class="photo u-photo" itemprop="image">' . $src . '</span>';
				} else {
					$pattern = '/src\=\"(.)*\"/U';
					preg_match_all( $pattern, $first_image, $source );
					if ( isset( $source ) && isset( $source[0] ) && isset( $source[0][0] ) ) {
						$src         = str_replace( '"', '', $source[0][0] );
						$src         = str_replace( 'data-src=', '', $src );
						$first_image = $first_image . '<span class="marlon-data" class="photo u-photo" itemprop="image">' . $src . '</span>';
					}
				}
			}

			if ( empty( $matches ) || empty( $matches[0] ) || empty( $matches[0][0] ) ) {
				$pattern = '/<img.+?src=[\'"]([^\'"]+)[\'"].*?>/i';
				preg_match_all( $pattern, $content, $matches );
				$first_image     = $matches[0][0];
				$first_image_url = $matches[1][0];
				$first_image     = $first_image . '<span class="marlon-data" class="photo u-photo" itemprop="image">' . $first_image_url . '</span>';
			}

			$image = apply_filters( 'marlon_first_image_of_post', $first_image, $this->post );
			$image = $args['before'] . $image . $args['after'];

			return $image;

		}
		public function get_the_first_image_of_post( $post_id = false ) {
			return $this->utility_get(
				$this->get_first_image_of_post( $post_id )
			);
		}
		public function the_first_image_of_post( $echo = true ) {
			return $this->utility_print( $this->get_the_first_image_of_post( get_the_ID() ), $echo );
		}

		private function get_content_without_first_image( $post_id = false ) {

			$this->setup_module( $post_id );

			$content = do_shortcode( apply_filters( 'the_content', $this->post->post_content ) );

			$content = preg_replace( '/<img[^>]+./', '', $content );
			$content = preg_replace( '/<figure[^>]*><\/figure[^>]*>/', '', $content );
			$content = str_replace( '<figure class="wp-block-image"><noscript></noscript></figure>', '', $content );
			$content = str_replace( '<noscript></noscript>', '', $content );
			$content = str_replace( '<p></p>', '', $content );

			return $content;

		}
		public function get_the_content_without_first_image( $post_id = false ) {
			return $this->utility_get(
				$this->get_content_without_first_image( $post_id )
			);
		}
		public function the_content_without_first_image( $echo = true ) {
			return $this->utility_print( $this->get_the_content_without_first_image( get_the_ID() ), $echo );
		}

		private function get_first_audio_of_post( $args = '', $post_id = false ) {

			$this->setup_module( $post_id );

			$args = wp_parse_args(
				$args,
				array(
					'before' => '',
					'after'  => '',
				)
			);

			$content = do_shortcode( apply_filters( 'the_content', $this->post->post_content ) );

			$pattern = '/<figure.+class="[^"]*?wp-block-audio[^"]*?".*>([^$]+?)<\/figure>/i';
			preg_match_all( $pattern, $content, $matches );
			if ( ! empty( $matches ) && ! empty( $matches[0] ) ) {
				$first_audio = $matches[0][0];
				$pattern     = '/src="(.*)"/i';
				preg_match_all( $pattern, $first_audio, $source );
				$first_audio = $source[1][0];
				$first_audio = wp_audio_shortcode( array( 'src' => $first_audio ) );
			} else {
				$pattern = '/<audio.+src=[\'"]([^\'"]+)[\'"].*>/i';
				preg_match_all( $pattern, $content, $matches );
				if ( ! empty( $matches ) && ! empty( $matches[0] ) ) {
					$first_audio = $matches[0][0];
				}
				if ( empty( $first_audio ) ) {
					$pattern = '/<figure.+class="[^"]*?wp-block-embed-soundcloud[^"]*?".*>([^$]+?)<\/figure>/i';
					preg_match_all( $pattern, $content, $matches );
					if ( ! empty( $matches ) && ! empty( $matches[0] ) ) {
						$first_audio = $matches[0][0];
					}
					if ( empty( $first_audio ) ) {
						return;
					}
				} else {
					$first_audio = explode( '?', $first_audio )[0];
					$first_audio = wp_audio_shortcode( array( 'src' => $first_audio ) );
				}
			}

			$audio = apply_filters( 'marlon_first_audio_of_post', $first_audio, $this->post );
			$audio = $args['before'] . $audio . $args['after'];

			return $audio;

		}
		public function get_the_first_audio_of_post( $post_id = false ) {
			return $this->utility_get(
				$this->get_first_audio_of_post( $post_id )
			);
		}
		public function the_first_audio_of_post( $echo = true ) {
			return $this->utility_print( $this->get_the_first_audio_of_post( get_the_ID() ), $echo );
		}

		private function get_content_without_first_audio( $post_id = false ) {

			$this->setup_module( $post_id );

			$content = $this->post->post_content;

			$content = preg_replace( '/\[audio(.*?)\[\/audio\]/i', '', $content );
			$content = do_shortcode( apply_filters( 'the_content', $content ) );
			$content = preg_replace( '/<figure.+class="[^"]*?wp-block-audio[^"]*?".*>([^$]+?)<\/figure>/i', '', $content );
			$content = preg_replace( '/<figure.+class="[^"]*?wp-block-embed-soundcloud[^"]*?".*>([^$]+?)<\/figure>/i', '', $content );
			$content = str_replace( '<p></p>', '', $content );

			return $content;
		}
		public function get_the_content_without_first_audio( $post_id = false ) {
			return $this->utility_get(
				$this->get_content_without_first_audio( $post_id )
			);
		}
		public function the_content_without_first_audio( $echo = true ) {
			return $this->utility_print( $this->get_the_content_without_first_audio( get_the_ID() ), $echo );
		}

		public function get_first_video_of_post( $args = '', $post_id = false ) {

			$this->setup_module( $post_id );

			$args = wp_parse_args(
				$args,
				array(
					'before' => '',
					'after'  => '',
				)
			);

			$poster  = '';
			$content = do_shortcode( apply_filters( 'the_content', $this->post->post_content ) );
			$pattern = '/<video.+src=[\'"]([^\'"]+)[\'"].*>/i';
			preg_match_all( $pattern, $content, $matches );

			if ( ! empty( $matches ) && ! empty( $matches[1] ) ) {
				$first_video = $matches[1][0];
			}

			if ( empty( $first_video ) ) {
				$embed = get_media_embedded_in_content( $content, array( 'video', 'embed', 'iframe' ) );
				if ( empty( $embed ) ) {
					return;
				}
				$first_video = $embed[0];
			} else {
				$pattern_poster = '/<video.+poster=[\'"]([^\'"]+)[\'"].*>/i';
				preg_match_all( $pattern_poster, $content, $matches_poster );
				if ( ! empty( $matches_poster ) && ! empty( $matches_poster[1] ) ) {
					$poster = $matches_poster[1][0];
				}
				$first_video = explode( '?', $first_video )[0];
				$first_video = wp_video_shortcode(
					array(
						'src'    => $first_video,
						'poster' => $poster,
						'width'  => 900,
					)
				);
			}

			$video = apply_filters( 'marlon_first_video_of_post', $first_video, $this->post );
			$video = $args['before'] . $video . $args['after'];

			return $video;

		}
		public function get_the_first_video_of_post( $post_id = false ) {
			return $this->utility_get(
				$this->get_first_video_of_post( $post_id )
			);
		}
		public function the_first_video_of_post( $echo = true ) {
			return $this->utility_print( $this->get_the_first_video_of_post( get_the_ID() ), $echo );
		}

		private function get_content_without_first_video( $post_id = false ) {

			$this->setup_module( $post_id );

			$content = $this->post->post_content;
			$content = preg_replace( '/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i', '', $content );
			$content = preg_replace( '/\s*[a-zA-Z\/\/:\.]*vimeo.com\/([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i', '', $content );
			$content = preg_replace( '/\[video(.*?)\[\/video\]/i', '', $content );
			$content = do_shortcode( apply_filters( 'the_content', $content ) );
			$content = preg_replace( '/<figure.+class=[\'"]wp-block-video[\'"].*>(.*?)<\/figure>/i', '', $content );
			$content = preg_replace( '/<figure.+class="[^"]*?wp-block-embed-youtube[^"]*?".*>([^$]+?)<\/figure>/i', '', $content );
			$content = preg_replace( '/<figure.+class="[^"]*?wp-block-embed-vimeo[^"]*?".*>([^$]+?)<\/figure>/i', '', $content );
			$content = str_replace( '<p></p>', '', $content );

			return $content;

		}
		public function get_the_content_without_first_video( $post_id = false ) {
			return $this->utility_get(
				$this->get_content_without_first_video( $post_id )
			);
		}
		public function the_content_without_first_video( $echo = true ) {
			return $this->utility_print( $this->get_the_content_without_first_video( get_the_ID() ), $echo );
		}

		private function get_first_gallery_of_post( $args = '', $post_id = false ) {

			$this->setup_module( $post_id );

			$args = wp_parse_args(
				$args,
				array(
					'before' => '',
					'after'  => '',
				)
			);

			$content = do_shortcode( apply_filters( 'the_content', $this->post->post_content ) );


			$pattern = '/<ul.+class="[^"]*?wp-block-gallery[^"]*?".*>([^$]+?)<\/ul>/i';
			preg_match_all( $pattern, $content, $matches );

			if ( ! isset( $matches ) || ! isset( $matches[0] ) || ! isset( $matches[0][0] ) ) {
				$pattern = '/<figure.+class="[^"]*?wp-block-gallery[^"]*?".*>([^$]+?)<\/figure>/i';
				preg_match_all( $pattern, $content, $matches );
			}

			$first_gallery = '';

			if ( isset( $matches ) && isset( $matches[0] ) && isset( $matches[0][0] ) ) {
				$first_gallery = $matches[0][0];
			}

			$first_gallery = str_replace( 'columns-1', '', $first_gallery );
			$first_gallery = str_replace( 'columns-2', '', $first_gallery );
			$first_gallery = str_replace( 'columns-3', '', $first_gallery );
			$first_gallery = str_replace( 'columns-4', '', $first_gallery );
			$first_gallery = str_replace( 'columns-5', '', $first_gallery );
			$first_gallery = str_replace( 'columns-6', '', $first_gallery );
			$first_gallery = str_replace( 'is-cropped', '', $first_gallery );
			$first_gallery = str_replace( 'wp-block-gallery', 'marlon-gallery', $first_gallery );
			$first_gallery = str_replace( 'blocks-gallery-grid', 'marlon-gallery-grid', $first_gallery );
			$first_gallery = str_replace( 'blocks-gallery-item', 'marlon-gallery-item', $first_gallery );

			$gallery = apply_filters( 'themeberger_first_gallery_of_post', $first_gallery, $this->post );
			$gallery = $args['before'] . $gallery . $args['after'];

			return $gallery;

		}
		public function get_the_first_gallery_of_post( $post_id = false ) {
			return $this->utility_get(
				$this->get_first_gallery_of_post( $post_id )
			);
		}
		public function the_first_gallery_of_post( $echo = true ) {
			return $this->utility_print( $this->get_the_first_gallery_of_post( get_the_ID() ), $echo );
		}

		private function get_content_without_first_gallery( $post_id = false ) {

			$this->setup_module( $post_id );

			$content = do_shortcode( apply_filters( 'the_content', $this->post->post_content ) );

			$content = preg_replace( '/<ul.+class="[^"]*?wp-block-gallery[^"]*?".*>([^$]+?)<\/ul>/i', '', $content );
			$content = preg_replace( '/<figure.+class="[^"]*?wp-block-gallery[^"]*?".*>([^$]+?)<\/figure>/i', '', $content );
			$content = str_replace( '<p></p>', '', $content );

			return $content;

		}
		public function get_the_content_without_first_gallery( $post_id = false ) {
			return $this->utility_get(
				$this->get_content_without_first_gallery( $post_id )
			);
		}
		public function the_content_without_first_gallery( $echo = true ) {
			return $this->utility_print( $this->get_the_content_without_first_gallery( get_the_ID() ), $echo );
		}

	}
}
