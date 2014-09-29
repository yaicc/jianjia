<?php

class topicController extends base_jianjia {

	public function indexAction() {
    	$this->getView()->assign("content", "Hello World");
	}

	public function postAction() {
		$topic_model = new TopicModel();
		$nodelist = $topic_model->nodelist();
		$request = $this->getRequest();
		if($request->isPost()) {
			$data = $request->getPost();
			if (empty($data['title'])) $this->getView()->assign('alert', helper_common::be_false('请输入标题'));
			elseif (empty($data['content'])) $this->getView()->assign('alert', helper_common::be_false('请输入内容'));
			elseif (helper_common::mbstrlen($data['content']) < 15) $this->getView()->assign('alert', helper_common::be_false('内容少于15字'));
			elseif (empty($nodelist[$data['node']])) $this->getView()->assign('alert', helper_common::be_false('无效的节点'));
			else {
				/* 话题入库 */
			}
		}
		$this->getView()->assign('nodelist', $nodelist);
	}
}