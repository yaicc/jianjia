<?php

/**
 * 通用辅助方法
 * 
*/

class helper_common {

	public static $domain;

	public static function be_true($data = '', $msg = '') {
		return array(
			'status' => true, 
			'data' => $data,
			'msg' => $msg
		);
	}

	public static function be_false($msg = '', $data = '') {
		return array(
			'status' => false, 
			'data' => $data,
			'msg' => $msg
		);
	}

	public static function uri($url = '') {
		echo self::get_uri($url);
	}

	public static function get_uri($url = '') {
		if (empty(self::$domain)) {
			self::$domain = Yaf_Registry::get("config")->customer->domain;
		}
		return 'http://'.self::$domain.'/'.trim($url, '/');
	}

	public static function redirect($uri, $message = '', $seconds = 0, $type = 'succeed') {
		$uri = stripos($uri, "http://") ? $uri : self::get_uri($uri);
		if ($seconds == 0) {
			//表示永远停留页面
			header("Location: ".$uri);
		}
		exit;
	}

	public static function authcode($string) {
		$key = Yaf_Registry::get("config")->customer->key;
		$string .= $key;
		$password = md5(substr(md5($string), 5, 20));
		return $password;
	}

	public static function time_format($time) {
		/* 计算出时间差 */
        $seconds = time() - $time;
        $minutes = floor($seconds / 60);
        $hours   = floor($minutes / 60);
        $days    = floor($hours / 24);
        /* 格式化输出 */
        $diff = '';
        if ($days > 0) {
        	if ($days < 2) $diff = '昨天';
        	elseif ($days < 15 && $days > 7) $diff = '上周';
        	elseif ($days > 30 && $days < 60) $diff = '上月';
        	elseif ($days < 70) $diff =  $days.'天前';
        	else $diff = date('Y-m-d', $time);
        } else {
        	if ($hours > 0) $diff = $hours.'小时前';
        	elseif ($minutes > 0) $diff = $minutes.'分钟前';
        	else $diff = '刚刚';
        }
        return $diff;
	}

	public static function pagenav($counts, $size, $uri, $page, $suffix = '') {
		if ($counts == 0 || $size > $counts) {
			return null;
		}
		$page_counts = ceil($counts/$size);
		if ($page > $page_counts) {
			self::_404();
		}
		$str = "";
		for ($i = 1; $i <= $page_counts; $i++) { 
			if ($page == $i) {
				$str .= '<li class="active"><a href="javascript:;">'.$i.'<span class="sr-only">(current)</span></a></li>';
			} else {
				$str .= '<li><a href="'.helper_common::get_uri($uri.'/'.$i.$suffix).'">'.$i.'</a></li>';
			}
		}
		if ($page == 1) {
			$str = '<li class="disabled"><a href="javascript:;">&laquo;</a></li>'.$str;
		} else {
			$str = '<li><a href="'.helper_common::get_uri($uri.'/'.($page-1)).$suffix.'">&laquo;</a></li>'.$str;
		}
		if ($page == $page_counts) {
			$str .= '<li class="disabled"><a href="javascript:;">&raquo;</a></li>';
		} else {
			$str .= '<li><a href="'.helper_common::get_uri($uri.'/'.($page+1)).$suffix.'">&raquo;</a></li>';
		}
		return '<div class="pagenav"><nav><ul class="pagination">'.$str.'</ul></nav></div>';
	}

	public static function array_addslashes($string) {
		if(empty($string)) return $string;
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = self::array_addslashes($val);
			}
		} else {
			$string = addslashes($string);
		}
		return $string;
	}

	public static function array_stripslashes($string) {
		if(empty($string)) return $string;
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = self::array_stripslashes($val);
			}
		} else {
			$string = stripslashes($string);
		}
		return $string;
	}

	public static function alert($message, $level = 'danger') {
		include ROOT_PATH.'/theme/'.theme.'/public/alert.html';
	}

	public static function mbstrlen($str) {
		/* 中文字数，utf8编码 */
		$strlen = strlen($str);
		$count = 0;
		for( $i = 0; $i<$strlen; $i++ ) {
			if(ord($str{$i}) >= 128) $i = $i + 3;
			$count++;
		}
		return $count;
	}

	public static function _404() {
		header("HTTP/1.1 404 Not Found");
		header("status: 404 Not Found");
		exit();
	}

	public static function system_error($message) {
		if (!Yaf_Registry::get('config')->customer->debug) return true;
		ob_end_clean();
		ob_start();

		$host = $_SERVER['HTTP_HOST'];
		echo <<<EOT
<!DOCTYPE html>
<html>
<head>
<title>$host - System Error</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" />
<style type="text/css">
<!--
body {
    background-color: #fff;
    margin: 40px;
    font: 13px/20px normal Helvetica, Arial, sans-serif;
    color: #4F5155;
}

h1 {
    color: #000;
    font-weight: 800;
    font-size: 36px;
	line-height: 40px;
}

code {
    font-family: Consolas, Monaco, Courier New, Courier, monospace;
    font-size: 14px;
    color: #333;
    display: block;
}

#body {
    margin: 10% 15px;
}

p.footer {
    font-size: 11px;
    border-top: 1px solid #D0D0D0;
    line-height: 32px;
}
-->
</style>
</head>
<body>
<div id="body">
<h1>$message</h1>
EOT;
		if($phpmsg = debug_backtrace()) {
			if(is_array($phpmsg)) {
				foreach($phpmsg as $k => $msg) {
					$k++;
					echo '<code>'.$k.'. ';
					if (isset($msg['file'])) {
						echo '[Line: '.$msg['line'].']'.$msg['file'];
					}
					echo '('.$msg['function'].')</code>';
				}
			} else {
				echo $phpmsg;
			}
		}
		echo <<<EOT
<p class="footer">jianjia.club Version 0.0.1</p>
</body>
</html>
EOT;
		exit();
	}
}