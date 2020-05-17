<?php

if ( ! class_exists( 'Site_Tools' ) ) {
	class Site_Tools extends Marlon_Module {

		protected function init_module() {

			$this->loader->add_filter( 'get_the_archive_title', $this, 'remove_category_prefix' );

			$this->loader->add_filter( 'the_content', $this, 'process_email' );
			$this->loader->add_filter( 'the_excerpt', $this, 'process_email' );
			$this->loader->add_filter( 'widget_text', $this, 'process_email' );

		}

		private function convert_to_ascii( $str ) {

			$pieces = str_split( trim( $str ) );
			$new_str = '';

			foreach( $pieces as $val ) {
				$new_str .= '&#' . ord( $val ) . ';';
			}

			return $new_str;

		}

		public function process_email( $content ) {

			$pattern = '/(mailto\:)?[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i';
			preg_match_all( $pattern, $content, $matches );

			foreach ( $matches[0] as $key => $replacement ) {
				$content = preg_replace( $pattern, $this->convert_to_ascii( $replacement ), $content, 1 );
			}

			return $content;

		}

		function remove_category_prefix( $title ) {

			if ( is_archive() ) {

				$pattern = '/([a-z]*):\s?/mi';
				$replacement = '<span class="nix-prefix">${1}</span>';
				$title = preg_replace( $pattern, $replacement, $title );

			}

			return $title;

		}

	}
}
