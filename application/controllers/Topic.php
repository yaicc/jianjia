<?php

class topicController extends base_jianjia {

	public function indexAction() {
    	$this->assign("content", "Hello World");
	}

	public function topicAction($tid, $page = 1) {

		$topic_model = new TopicModel();

		if ($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost();
			$ret = $topic_model->add_comment($data, $tid);
			if (!$ret['status']) {
				$this->assign('comment_alert', $ret);
			} else {
				helper_common::redirect('topic/'.$tid.'/#comment-'.$ret['data']);
			}
		}

		$topic = $topic_model->topic($tid, $page);
		if ($topic['status']) {
			$topic = $topic['data'];

			$pagenav = helper_common::pagenav($topic['comment_info']['counts'], 15, 'topic/'.$tid, $page, '#comments');

			/* app data*/
			$this->app['crumbs'] = $topic['title'];
			$this->app['menu_active'] = 'topic';
			$this->app['topic_right'] = true;

			$this->assign('app', $this->app);
			$this->assign('topic', $topic);
			$this->assign('pagenav', $pagenav);
		} else {
			/* 404 */
			helper_common::_404();
		}
	}

	public function nodeAction($alias, $page = 1) {

		$topic_model = new TopicModel();

		$nodelist = $topic_model->topic_list_node($alias, $page);
		if ($nodelist['status']) {

			$pagenav = helper_common::pagenav($topic_model->topic_total($nodelist['data'][0]['nid']), 15, 'node/'.$alias, $page);
    		$this->assign('pagenav', $pagenav);
			$this->assign('list', $nodelist['data']);
			/* app data*/
			$this->app['crumbs'] = $nodelist['data'][0]['node_info']['nodename'];
			$this->app['menu_active'] = 'topic';
			$this->assign('app', $this->app);

		} else {
			helper_common::_404();
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