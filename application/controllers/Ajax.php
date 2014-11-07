<?php

class ajaxController extends Yaf_Controller_Abstract {
	
	function init() {
		/* 判断来路 */
		if(!$this->_request->isXmlHttpRequest()) {
			helper_common::_404();
		}
		/* 关闭视图 */
    	Yaf_Dispatcher::getInstance()->disableView();
	}

	public function signAction() {
		$type = $this->getRequest()->getQuery("type");
		$value = addslashes(trim($this->getRequest()->getQuery("value")));
		if (!$type || !$value) {
			exit('false');
		}
		$db = Yaf_Registry::get('db');
		if ($type == 'email') {
			if ($db->fetch_row("select uid from member where email = '$value'")) {
				exit('false');
			} else {
				exit('true');
			}
		} elseif ($type == 'nickname') {
			if ($db->fetch_row("select uid from member where name = '$value'")) {
				exit('false');
			} else {
				exit('true');
			}
		}
	}

	public function uploadAction() {
		header("Content-type:application/json;charset=utf-8");
		if (!Yaf_Registry::get('_u')) {
			exit(json_encode(array(
				'success' => false,
				'msg' => '请先登录社区'
			)));
		} else {
			/* 上传配置 */
			$file = $this->getRequest()->getFiles('upload_image');
			$upload = helper_upload::upload_image($file);
			exit(json_encode($upload));
		}
	}
}