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

	public function register($data) {
		foreach ($data as $key => $value) {
			$data[$key] = trim($value);
		}
		/* data valid*/
		if (empty($data['email'])) return helper_common::be_false('请输入帐号(邮箱)');
		if (empty($data['password'])) return helper_common::be_false('请输入密码');
		if ($data['password'] != $data['confirm_password']) return helper_common::be_false('两次输入的密码不匹配');
		if (empty($data['nickname'])) return helper_common::be_false('请输入一个昵称');
		if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) return helper_common::be_false('无效的Email地址');
		if (!preg_match('/^\w+$/', $data['password'])) return helper_common::be_false('密码只支持数字和字母的组合');
		if (preg_match('/[\!\@\#\$\^&\*\(\)\-\=\+`\:\;\'\"\‘\“\’\”\,\.\/\\\?]+/', $data['nickname'], $match)) {
			var_dump($match);
			return helper_common::be_false('昵称应为字母、数字和汉字的组合');
		}
		/* valid end */
		$data = helper_common::array_addslashes($data);
		$is_email = $this->db->fetch_row(sprintf("select uid from member where `email` = '%s'", $data['email']));
		if ($is_email) {
			return helper_common::be_false('该邮箱已经注册过了');
		}
		$is_nickname = $this->db->fetch_row(sprintf("select uid from member where `name` = '%s'", $data['nickname']));
		if ($is_nickname) {
			return helper_common::be_false('该昵称已经被占用了');
		}
		/* insert */
		$rs = $this->db->insert('member', array(
			'email' => $data['email'],
			'password' => helper_common::authcode($data['password']),
			'name' => $data['nickname'],
			'regdate' => time()
		));
		if ($rs) {
			return helper_common::be_true();
		} else {
			return helper_common::be_false('系统错误，请稍候再试');
		}
	}

	public function login($email, $password, $admin = 0) {
		if (!$email || !$password) {
			return helper_common::be_false('邮箱或密码不能为空！');
		}
		$email = addslashes($email);
		$user = $this->db->fetch_row("select * from member where `email` = '$email'");
		if ($user) {
			if ($user['password'] == helper_common::authcode($password)) {
				if ($admin != 0 && $user['adminid'] == 0) return helper_common::be_false('没有权限！');
				else {
					$this->save_session($user, $admin);
					return helper_common::be_true();
				}
			} else {
				return helper_common::be_false('密码错误，马上 <a href="'.helper_common::get_uri('user/retrieve').'">找回密码</a>');
			}
		} else {
			return helper_common::be_false('未注册的帐号，马上前往 <a href="'.helper_common::get_uri('register').'">注册</a>');
		}
	}

	public function auth() {
		$request = Yaf_Dispatcher::getInstance()->getRequest();
		if ( !empty($auth_token = $request->getCookie('auth_token')) && !empty($auth_id = $request->getCookie('auth_id')) ) {
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

	public function del_session() {
		if ($this->ss->get('adminid')) {
			$this->ss->del('adminid');
		}
		setcookie("auth_token", "", time()-1, '/');
		setcookie("auth_id", "", time()-1, '/');
	}
}