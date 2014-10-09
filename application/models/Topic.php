<?php

/**
 * topic 模型类
 * 
*/

class TopicModel {

	private $db;

	function __construct() {
		//初始化数据库连接
		$this->db = Yaf_Registry::get('db');
	}

	public function nodelist() {
		//节点列表
		if (!$nodelist = Yaf_Registry::get('memcache')->get('jj_nodelist')) {
			$rs = $this->db->fetch_all("select * from `node` order by nid asc");
			$nodelist = array();
			foreach ($rs as $key => $value) {
				$nodelist[$value['nid']] = $value;
			}
			Yaf_Registry::get('memcache')->set('jj_nodelist', $nodelist, MEMCACHE_COMPRESSED, 0);
		}
		return $nodelist;
	}

	public function add($request) {
		//添加话题

		/* user */
		$user = Yaf_Registry::get('_u');

		$data = array();
		$data['nid'] = $request['node'];
		$data['uid'] = $user['uid'];
		$data['username'] = $user['name'];
		$data['title'] = $request['title'];
		$data['content'] = $request['content'];
		$data['postdate'] = time();

		return $this->db->insert('topic', $data, true);
	}
}