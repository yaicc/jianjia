<?php

/**
 * 项目后台控制器基类
 * 
*/
class base_admin extends Yaf_Controller_Abstract {
	
	/**
	 * @var user
	*/
	protected $user;

	/**
	 * @var info
	*/
	protected $info = array();

	function init() {

		Yaf_Dispatcher::getInstance()->disableView();

		$this->user = Yaf_Registry::get('_u');
		$session = Yaf_Session::getInstance();

		if ((empty($session->get('adminid')) || $this->user == false) && $this->getRequest()->getActionName() != 'login') {
			$this->redirect('/admin/admin/login');
		}

		/* 网站后台权限初始化 */
		$this->permissions();
	}

	private function permissions() {
		$request = $this->getRequest();
		$this->info['controller'] = $request->getControllerName();
		$this->info['action'] = $request->getActionName();
		$auth_name = $this->info['controller'].$this->info['action'];
		if ($aid = Yaf_Registry::get('db')->fetch_field("select aid from authorization where alias = '$auth_name'")) {
			if (!($this->user['adminid'] & $aid)) {
				exit('没有权限');
			}
		}
	}

	protected function show() {
		/* 视图 */
		$this->initView();
		$this->setViewPath(ROOT_PATH."/application/modules/admin/views");
		$this->getView()->assign("controller", $this->info['controller']);
		$this->getView()->assign("action", $this->info['action']);
		$this->display('../admin');
	}
}