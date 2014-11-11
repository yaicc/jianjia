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

	private function isLogin() {
		if (Yaf_Registry::get("_u")) {
			return true;
		} else {
			return false;
		}
	}

	public function loginAction() {
		if ($this->isLogin()) {
			header("Location: ".Yaf_Registry::get('_g')['refer']);
		}
		$request = $this->getRequest();
		if($request->isPost()) {
			$data = $request->getPost();
			$user = new UserModel();
			$login = $user->login($data['email'], $data['password']);
			if ($login['status']) {
				//helper_common::redirect();
				header("Location: ".Yaf_Registry::get('_g')['refer']);
			} else {
				$this->assign('alert', $login);
			}
		}
	}

	public function registerAction() {
		if ($this->isLogin()) {
			header("Location: ".Yaf_Registry::get('_g')['refer']);
		}
		$request = $this->getRequest();
		if($request->isPost()) {
			$data = $request->getPost();
			$user = new UserModel();
			$register_rs = $user->register($data);
			if (!$register_rs['status']) {
				$this->assign('alert', $register_rs);
			} else {
				$user->login($data['email'], $data['password']);
				helper_common::redirect();
			}
			$this->assign('data', $data);
		}
	}

	public function loginoutAction() {
		if (!$this->isLogin()) {
			helper_common::redirect('login');
		}
		$user = new UserModel();
		$user->del_session();
		helper_common::redirect();
	}
}