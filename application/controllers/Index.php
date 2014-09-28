<?php

class indexController extends base_jianjia {
	
	public function indexAction() {
    	$this->getView()->assign("content", "Hello World");
	}
}