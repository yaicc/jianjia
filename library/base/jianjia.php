<?php

/**
 * 项目控制器基类
 * 
*/
class base_jianjia extends Yaf_Controller_Abstract {
	
	/**
	 * @var app_info
	*/
	protected $app = array();

	function init() {
		$this->setViewPath(ROOT_PATH."/theme/".theme."/");
	}

	protected function assign($alias, $data) {
		$view = $this->getView();
		if(empty($this->app['assign_key'])) {
			$this->app['assign_key'] = 1;
			/* 公共数据渲染 */
			$view->assign('app', $this->app);
		}
		$view->assign($alias, $data);
	}
}