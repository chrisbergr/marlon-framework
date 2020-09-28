<?php

if ( ! class_exists( 'Site_Breadcrumbs' ) ) {
	class Site_Breadcrumbs extends Marlon_Module {

		private $items    = array();
		private $settings = array();

		protected function init_module() {
			$this->settings = array(
				'seperator'         => '',
				'prefix'            => __( 'You are here: ', 'marlon' ),
				'home_label'        => __( 'Home', 'marlon' ),
				'404_label'         => __( 'Not Found', 'marlon' ),
				'html_container'    => '<nav class="marlon-breadcrumbs site-breadcrumbs" aria-label="Breadcrumb"><ol itemscope itemtype="https://schema.org/BreadcrumbList">%s</ol></nav>',
				'html_item'         => '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="%1$s"><span itemprop="name">%2$s</span></a><meta itemprop="position" content="%3$s" /></li>',
				'html_current_item' => '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a itemprop="item" href="%1$s" aria-current="page"><span itemprop="name">%2$s</span></a><meta itemprop="position" content="%3$s" /></li>',
				'language_folders'  => array( 'en/', 'de/' ),
			);
			$this->theme_setup();
		}

		private function theme_setup() {
			$this->loader->add_action( 'marlon_site_breadcrumbs', $this, 'breadcrumbs', 96 );
		}

		private function style_setup(){
			if ( ! current_theme_supports( 'marlon-breadcrumbs' ) ) {
				$this->loader->add_action( 'wp_head', $this, 'print_styles' );
			}
		}

		public function print_styles() {
			$style = '
				.marlon-breadcrumbs{
					display: inline-block;
				}
				.marlon-breadcrumbs ol{
					display: inline;
					list-style: none;
					margin-left: 0;
					padding-left: 0;
					margin-bottom: 0;
					font-size: 1em;
					line-height: 1;
				}
				.marlon-breadcrumbs li{
					display: inline;
				}
				.marlon-breadcrumbs li::after {
					content: "\002F";
					padding: 0 0.3em;
				}
				.marlon-breadcrumbs li:last-of-type::after {
					display: none;
				}
				.marlon-breadcrumbs li:last-of-type a {
					color: inherit;
					text-decoration: none;
				}
			';
			$style = apply_filters( 'marlon_breadcrumbs_inline_style', trim( str_replace( array( "\r", "\n", "\t", "  " ), '', $style ) ) );
			if ( $style ) {
				echo "\n" . '<style type="text/css" id="marlon-breadcrumbs-css">' . $style . '</style>' . "\n";
			}
		}

		private function breadcrumbs_get( $data, $post_id = false ) {
			return $data;
		}

		private function breadcrumbs_print( $data, $echo = true ) {
			if ( ! $echo ) {
				return $data;
			}
			echo $data;
		}

		private function get_current_lang_folder( $request ) {
			$folders = $this->get_language_folders();
			foreach( $folders as $folder ) {
				if ( substr( $request, 0, strlen( $folder ) ) === $folder ) {
					return '/' . str_replace( '/', '', $folder );
				}
			}
			return '';
		}

		private function get_seperator() {
			return $this->settings['seperator'];
		}

		private function get_prefix() {
			return $this->settings['prefix'];
		}

		private function get_home_label() {
			return $this->settings['home_label'];
		}

		private function get_404_label() {
			return $this->settings['404_label'];
		}

		private function get_html_container() {
			return $this->settings['html_container'];
		}

		private function get_html_item() {
			return $this->settings['html_item'];
		}

		private function get_html_current_item() {
			return $this->settings['html_current_item'];
		}

		private function get_language_folders() {
			return $this->settings['language_folders'];
		}

		private function receive_data_by_slug( $slug ) {
			$type = 'post';
			$data = get_page_by_path( $slug, OBJECT, array( 'post', 'page', 'product', 'pins' ) );
			if ( ! $data ) {
				$type = 'taxonomy';
				foreach ( get_taxonomies( array( 'public' => true ) ) as $key => $value ) {
					$data = get_term_by( 'slug', $slug, $value );
					if ( $data ) {
						break;
					}
				}
			}
			if ( ! $data ) {
				$type = 'custom_post_type';
				$data = get_post_type_object( $slug );
			}
			if ( ! $data ) {
				if ( strpos( $slug, '/' ) !== false ) {
					$new_slug = ltrim( strstr( $slug, '/' ), '/' );
					return $this->receive_data_by_slug( $new_slug );
				}
				return false;
			}
			return array(
				'data' => $data,
				'type' => $type,
			);
		}

		private function collect() {
			global $wp, $post;
			$langfolder = $this->get_current_lang_folder( $wp->request );
			$url =  add_query_arg( $wp->query_vars, home_url( $wp->request ) );
			if( function_exists( 'pll_home_url' ) ) {
				$url = str_replace( home_url( '/' ), pll_home_url( pll_current_language() ), $url );
			}
			$url = explode( '//', $url );
			$protocol = $url[0] . '//';
			$url = explode( '?', $url[1] )[0];
			$url_parts = explode( '/', $url );
			$count = 0;
			$items_count = count( $url_parts ) - 1;
			foreach ( $url_parts as $key => $value ) {
				$url_before = $protocol;
				if ( $count > 0 ) {
					$item_before = $this->items[$count - 1];
					$url_before = $item_before['url'] . '/';
				}
				$item_url = $url_before . $value;
				if ( $count === 0 ) {
					$item_url = $item_url . $langfolder;
				}
				$item_title = $this->get_home_label();
				if ( $count > 0 ) {
					$url = ltrim( str_replace( $this->items[0]['url'], '', $item_url ), '/' );
					$data = $this->receive_data_by_slug( $url );
					if ( $data ) {
						if ( $data['type'] === 'post' ) {
							$item_title = $data['data']->post_title;
							//$item_url = get_permalink( $data['data']->ID );
						}
						if ( $data['type'] === 'taxonomy' ) {
							$item_title = $data['data']->name;
							$item_url = get_term_link( $data['data']->term_id );
						}
						if ( $data['type'] === 'custom_post_type' ) {
							$item_title = $data['data']->label;
							//$item_url = get_term_link( $data['data']->term_id );
						}
						if ( ! $item_title || $item_title === '' ) {
							$item_title = $data['data']->ID;
						}
					} else {
						array_splice( $url_parts, $count, 1 );
						continue;
					}
				}
				$current_item = $count === $items_count;
				$item = array(
					'url'      => $item_url,
					'title'    => $item_title,
					'position' => $count + 1,
					'current'  => $current_item,
				);
				$this->items[] = $item;
				$count++;
			}
			//die();
		}

		private function get_html_items() {
			$items = array();
			foreach ( $this->items as $key => $value ) {
				$template = $this->get_html_item();
				if ( $value['current'] ) {
					$template = $this->get_html_current_item();
				}
				$items[] = sprintf(
					$template,
					$value['url'],
					$value['title'],
					$value['position'],
					$value['current']
				);
			}
			return $items;
		}

		private function get_html() {
			$items = join( $this->get_seperator(), $this->get_html_items() );
			$html = sprintf( $this->get_html_container(), $items );
			return $html;
		}

		private function output( $args = '' ) {
			if ( is_front_page() ) {
				return '';
			}
			$args = wp_parse_args(
				$args,
				array(
					'before' => '',
					'after'  => '',
				)
			);
			$items = $this->get_html();
			$breadcrumb = $this->get_prefix() . $items;
			$breadcrumb = apply_filters( 'marlon_breadcrumbs', $breadcrumb );
			$output = $args['before'] . $breadcrumb . $args['after'];
			return $output;
		}

		private function get_404_label_text() {
			return $this->get_404_label();
		}

		public function get_the_breadcrumbs( $before = '', $after = '', $post_id = false ) {
			if ( is_404() ) {
				return $before . $this->get_404_label_text() . $after;
			}
			$this->settings = apply_filters( 'marlon_breadcrumbs_settings', $this->settings );
			$this->style_setup();
			$this->collect();
			return $this->breadcrumbs_get(
				$this->output(
					array(
						'before' => $before,
						'after'  => $after,
					)
				)
			);
		}
		public function the_breadcrumbs( $before = '', $after = '', $echo = true ) {
			return $this->breadcrumbs_print( $this->get_the_breadcrumbs( $before, $after, get_the_ID() ), $echo );
		}
		public function breadcrumbs() {
			$this->the_breadcrumbs();
		}

	}
}
