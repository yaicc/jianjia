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

	function init() {

		$this->user = Yaf_Registry::get('_u');
		$session = Yaf_Session::getInstance();

		if ((empty($session->get('adminid')) || $this->user == false) && $this->getRequest()->getActionName() != 'login') {
			$this->redirect('/admin/admin/login');
		}
	}
}