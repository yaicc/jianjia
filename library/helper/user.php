<?php

/**
 * 用户辅助方法
 * 
*/

class helper_user {

	public static function avatar($path, $size = '') {
		$file = $size ? $path.'_'.$size.'.jpg' : $path.'jpg';
		echo '<img src="'.helper_common::get_uri().'/uploads/avatar/'.$file.'">';
	}
}