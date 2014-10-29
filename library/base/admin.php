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

		/* 网站后台信息初始化 */
		$this->init_info();

		/* 网站后台权限初始化 */
		$this->permissions();
	}

	private function init_info() {
		$request = $this->getRequest();
		$this->info['controller'] = strtolower($request->getControllerName());
		$this->info['action'] = strtolower($request->getActionName());
		$this->load('menu/menu'.ucfirst($this->info['controller']).'.php');
	}

	private function permissions() {
		if (isset($this->info['alias'])) {
			$auth_name = $this->info['alias'];
			if ($aid = Yaf_Registry::get('db')->fetch_field("select `aid` from authorization where alias = '$auth_name'")) {
				if (!($this->user['adminid'] & $aid)) {
					exit('没有权限');
				}
			}
		}
	}

	protected function show() {
		/* 视图 */
		$this->initView();
		$this->setViewPath(APP_PATH."/modules/admin/views");
		$this->getView()->assign("info", $this->info);
		$this->display('../admin');
	}

	protected function load($file) {
		/* 加载文件*/
		$path = APP_PATH.'/modules/admin/';
		include $path.$file;
		if (isset($menu) && isset($menu[$this->info['controller']][$this->info['action']])) {
			$info = $menu[$this->info['controller']][$this->info['action']];
			$this->info['alias'] = $info[0];
			$this->info['name'] = $info[1];
		}
	}
}