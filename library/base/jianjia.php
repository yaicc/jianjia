<?php

/**
 * 项目控制器基类
 * 
*/
class base_jianjia extends Yaf_Controller_Abstract {
	
	/**
	 * @var user_info
	*/
	protected $_user = array();

	function init() {
		$this->setViewPath(ROOT_PATH."/theme/".theme."/");
	}
}