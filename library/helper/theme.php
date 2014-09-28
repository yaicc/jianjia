<?php

/**
 * 主题辅助方法
 * 
*/

class helper_theme {

	public static function header() {

		include ROOT_PATH.'/theme/'.theme.'/public/head.html';
	}

	public static function footer() {

		include ROOT_PATH.'/theme/'.theme.'/public/foot.html';
	}

	public static function right() {

		include ROOT_PATH.'/theme/'.theme.'/public/right.html';
	}
}