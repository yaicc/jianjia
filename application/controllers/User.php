<?php

class userController extends base_jianjia {

	function init() {
		base_jianjia::init();
		$request = $this->getRequest();
		$action = strtolower($request->getActionName());
		switch ($action) {
			case 'register':
				$this->app['crumbs'] = "注册";
				break;
		}
		$this->assign('app', $this->app);
	}

	public function loginAction() {
		$request = $this->getRequest();
		if($request->isPost()) {
			$data = $request->getPost();
			if (empty($data['email'])) $this->assign('alert', helper_common::be_false('请输入帐号(邮箱)'));
			elseif (empty($data['password'])) $this->assign('alert', helper_common::be_false('请输入密码'));
		}
	}

	public function registerAction() {
		
	}
}