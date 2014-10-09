<?php

class topicController extends base_jianjia {

	public function indexAction() {
    	$this->assign("content", "Hello World");
	}

	public function postAction() {

		/* app data*/
		$this->app['crumbs'] = "发表话题";
		
		$topic_model = new TopicModel();
		$nodelist = $topic_model->nodelist();
		$request = $this->getRequest();
		if($request->isPost()) {
			$data = $request->getPost();
			if (empty($data['title'])) $this->assign('alert', helper_common::be_false('请输入标题'));
			elseif (empty($data['content'])) $this->assign('alert', helper_common::be_false('请输入内容'));
			elseif (helper_common::mbstrlen($data['content']) < 15) $this->assign('alert', helper_common::be_false('内容少于15字'));
			elseif (empty($nodelist[$data['node']])) $this->assign('alert', helper_common::be_false('无效的节点'));
			else {
				/* 话题入库 */
				if($tid = $topic_model->add($data)) {
					helper_common::redirect('topic/'.$tid);
				}
				$this->assign('alert', helper_common::be_false('应用程序错误，暂时无法发表话题'));
			}
		}
		$this->assign('nodelist', $nodelist);
	}
}