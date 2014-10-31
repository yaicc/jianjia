<?php

/**
 * 项目控制器基类
 * 
*/
class base_jianjia extends Yaf_Controller_Abstract {
	
	/**
	 * @var app_info
	 * crumbs 面包屑标志
	 * keywords 关键字
	 * description 描述
	*/
	protected $app = array();

	function init() {
		$this->setViewPath(ROOT_PATH."/theme/".theme."/");
	}

	protected function assign($alias, $data) {
		$view = $this->getView();
		$view->assign($alias, $data);
	}
}