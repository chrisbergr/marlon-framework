<?php

if ( ! class_exists( 'Marlon_Module' ) ) {
	class Marlon_Module extends Marlon_Utilities {

		public function init() {
			return $this->init_module();
		}

		protected function init_module() {
			//NOTE: overwrite in modules
		}

	}
}
