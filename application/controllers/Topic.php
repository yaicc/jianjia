<?php

class topicController extends base_jianjia {

	public function indexAction() {
    	$this->assign("content", "Hello World");
	}

	public function topicAction() {

	}

	public function postAction() {

		/* app data*/
		$this->app['crumbs'] = "发表话题";

		if (!Yaf_Registry::get('_u')) {
			helper_common::redirect('login');
		}
		
		$topic_model = new TopicModel();
		$nodelist = $topic_model->nodelist();
		$request = $this->getRequest();
		if($request->isPost()) {
			$data = $request->getPost();
			$topic_add = $topic_model->add($data);
			if ($topic_add['status']) {
				exit(helper_common::get_uri()."topic/".$topic_add['data']);
			} else {
				$this->assign('alert', $topic_add);
				$this->assign('data', $data);
			}
		}
		$this->assign('nodelist', $nodelist);
	}
}