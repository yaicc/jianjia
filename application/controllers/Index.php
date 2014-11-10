<?php

class indexController extends base_jianjia {
	
	public function indexAction($page = 0) {
    	$topic = new TopicModel();
    	$topic_list = $topic->topic_list_index($page);
    	$this->assign('list', $topic_list);
	}
}