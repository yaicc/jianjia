<?php

class topicController extends base_jianjia {

	public function indexAction() {
    	$this->getView()->assign("content", "Hello World");
	}

	public function postAction() {
		$request = $this->getRequest();
		if($request->isPost()) {
			$data = $request->getPost();
			if (empty($data['title'])) $this->getView()->assign('alert', helper_common::be_false('请输入标题'));
			elseif (empty($data['content'])) $this->getView()->assign('alert', helper_common::be_false('请输入内容'));
		}
	}
}