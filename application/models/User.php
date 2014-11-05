<?php

/**
 * user 模型类
 * 
*/

class UserModel {

	private $db;

	private $ss;

	function __construct() {
		//初始化数据库连接
		$this->db = Yaf_Registry::get('db');
		$this->ss = Yaf_Session::getInstance();
	}

	public function login($email, $password, $admin = 0) {
		if (!$email || !$password) {
			return helper_common::be_false('邮箱或密码不能为空！');
		}
		$email = addslashes($email);
		$user = $this->db->fetch_row("select * from member where `email` = '$email'");
		if ($user && $user['password'] == helper_common::authcode($password)) {
			if ($admin != 0 && $user['adminid'] == 0) return helper_common::be_false('没有权限！');
			else {
				$this->save_session($user, $admin);
				return helper_common::be_true();
			}
		} else {
			return helper_common::be_false('密码错误！');
		}
	}

	public function auth() {
		if (isset($_COOKIE['auth_token']) && isset($_COOKIE['auth_id'])) {
			$auth_token = $_COOKIE['auth_token'];
			$auth_id = $_COOKIE['auth_id'];
			$session_id = session_id();
			if (!empty($auth_token) && !empty($auth_id) && !empty($session_id) && $auth_token == helper_common::authcode($auth_id).helper_common::authcode($session_id)) {
				$uid = intval($auth_id);
				$user = $this->db->fetch_row("select * from member where `uid` = '$uid'");
				if ($user) {
					//$this->save_session($user);
					return $user;
				} else 
					return false;
			} else {
				return false;
			}
		}
	}

	private function save_session($user, $admin = 0) {
		$session_id = session_id();
		$auth_token = helper_common::authcode($user['uid']).helper_common::authcode($session_id);
		if ($admin != 0) {
			$this->ss->set('adminid', $user['adminid']);
		}
		/* cookies */
		setcookie("auth_token", $auth_token, 0, '/');
		setcookie("auth_id", $user['uid'], 0, '/');
	}
}