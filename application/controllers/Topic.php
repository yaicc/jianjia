<?php

class topicController extends base_jianjia {

	public function indexAction() {
    	$this->assign("content", "Hello World");
	}

	public function topicAction($tid) {

		$topic_model = new TopicModel();

		$topic = $topic_model->topic($tid);
		if ($topic['status']) {
			$topic = $topic['data'];

			/* app data*/
			$this->app['crumbs'] = $topic['title'];
			$this->app['topic_right'] = true;

			$this->assign('app', $this->app);
			$this->assign('topic', $topic);
		} else {
			/* 404 */
			helper_common::_404();
		}

		if ($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			$ret = $topic_model->add_comment($data, $tid);
			if (!$ret['status']) {
				$this->assign('comment_alert', $ret);
			}
		}
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