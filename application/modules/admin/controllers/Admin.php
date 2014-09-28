<?php

class adminController extends base_admin {

	public function indexAction() {
		
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
	}
}