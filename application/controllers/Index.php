<?php

class indexController extends base_jianjia {
	
	public function indexAction($page = 1) {
    	$topic = new TopicModel();
    	$topic_list = $topic->topic_list_index($page);
    	if (!$topic_list['status']) {
    		helper_common::_404();
    	}
    	$this->assign('list', $topic_list['data']);
    	if ($page != 1) {
    		/* 分页 */
    		$pagenav = helper_common::pagenav($topic->topic_total(), 15, 'list', $page);
    		$this->assign('pagenav', $pagenav);
    	}
	}
}