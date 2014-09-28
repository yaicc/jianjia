<?php

class pageController extends Yaf_Controller_Abstract {
	
	public function indexAction() {
    	$this->getView()->assign("content", "Hello World");
	}

	public function markdownAction() {
		
	}
}