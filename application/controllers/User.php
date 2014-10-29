<?php

class userController extends base_jianjia {

	public function loginAction() {
		$request = $this->getRequest();
		if($request->isPost()) {
			$data = $request->getPost();
			if (empty($data['email'])) $this->assign('alert', helper_common::be_false('请输入帐号(邮箱)'));
			elseif (empty($data['password'])) $this->assign('alert', helper_common::be_false('请输入密码'));
		}
	}
}