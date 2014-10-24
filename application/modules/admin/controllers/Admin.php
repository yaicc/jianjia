<?php

class adminController extends base_admin {

	public function indexAction() {
		$this->getView()->assign("content", "Hello World");
		$this->show();
	}

	public function loginAction() {
		$request = $this->getRequest();
		if($request->isPost()) {
			$data = $request->getPost();
			$user_model = new UserModel();
			$user = $user_model->login($data['email'], $data['password'], 1);
			if (!$user['status']) {
				exit($user['msg']);
			}
			$this->redirect('/admin/');
		}
		$this->initView();
		$this->display('login');
	}

	public function profileAction() {
		$this->show();
	}
}