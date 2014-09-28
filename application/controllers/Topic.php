<?php

class topicController extends base_jianjia {

	public function indexAction() {
    	$this->getView()->assign("content", "Hello World");
	}

	public function postAction() {
		
	}
}