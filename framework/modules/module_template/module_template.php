<?php

if ( ! class_exists( 'Module_Template' ) ) {
	class Module_Template extends Marlon_Module {

		private $random = '';
		protected function init_module() {
			$this->random = rand( 1, 20 );
		}
		public function info() {
			return 'Module Template (' . $this->random . ')';
		}

	}
}
