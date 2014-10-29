<?php

/**
 * 主题辅助方法
 * 
*/

class helper_theme {

	public static function header() {

		return ROOT_PATH.'/theme/'.theme.'/public/head.html';
	}

	public static function footer() {

		return ROOT_PATH.'/theme/'.theme.'/public/foot.html';
	}

	public static function right() {

		return ROOT_PATH.'/theme/'.theme.'/public/right.html';
	}

	public static function template($path) {
		return ROOT_PATH.'/theme/'.theme.'/'.rtrim($path, '/').'.html';
	}
}