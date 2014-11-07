<?php

/**
 * 上传辅助方法
 * 
*/

class helper_upload {

	public static function upload_image($file) {

		$config = array(
			'type' => array("image/gif","image/jpeg"),
			'size' => 500,
			'dir' => '/uploads'
		);

		if (!is_uploaded_file($file['tmp_name'])) {
			return self::be_false('无效的上传');
		}
		if (!in_array($file['type'], $config['type'])) {
			return self::be_false("无效的文件类型！"); 
		}
		if ($file['size'] > $config["size"]*1024) {
			return self::be_false("上传的文件大小不能超过".$config["size"]."KB！"); 
		}
		$filearr = pathinfo($file['name']); 
		$filetype = $filearr["extension"]; 
		$file_abso = $config["dir"]."/".date('Ym', time())."/".md5($file['name']).'.'.$filetype;
		if (file_exists(ROOT_PATH.$file_abso)) {
			$file_abso = $config["dir"]."/".date('Ym', time())."/".md5($file['name'].'_'.time()).'.'.$filetype;
		}
		if (move_uploaded_file($file['tmp_name'], ROOT_PATH.$file_abso)) {
			return self::be_true($file_abso);
		} else {
			return self::be_false("文件上传失败");
		}
	}

	private static function be_false($msg) {
		return array(
			'success' => false,
			'msg' => $msg
		);
	}

	private static function be_true($file) {
		return array(
			'success' => true,
			'file_path' => helper_common::get_uri().$file
		);
	}
}