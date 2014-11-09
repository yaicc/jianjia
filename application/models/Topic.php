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

	public function topic_list($start, $limit, $order, $node = 0) {
		//话题列表
		if ($node == 0) {
			$list = $this->db->fetch_all("select * from topic where `status` = 0 order by $order limit $start, $limit");
		} elseif ($node != 0) {
			$list = $this->db->fetch_all("select * from topic where `status` = 0 and nid = $node order by $order limit $start, $limit");
		}
	}

	public function add($request) {
		//添加话题

		/* user */
		$user = Yaf_Registry::get('_u');
		if (!$user) {
			return helper_common::be_false("请先登录");
		}

		$nodelist = $this->nodelist();

		if (empty(trim($request['title']))) return helper_common::be_false('请输入标题');
		elseif (empty($request['content'])) return helper_common::be_false('请输入内容');
		elseif (helper_common::mbstrlen($request['content']) < 15) return helper_common::be_false('内容少于15字');
		elseif (empty($nodelist[$request['node']])) return helper_common::be_false('无效的节点');

		/* content 内容过滤 */

		$request = helper_common::array_addslashes($request);

		$data = array();
		$data['nid'] = $request['node'];
		$data['uid'] = $user['uid'];
		$data['username'] = $user['name'];
		$data['title'] = trim($request['title']);
		$data['content'] = $request['content'];
		$data['postdate'] = time();

		if ($tid = $this->db->insert('topic', $data, true)) {
			return helper_common::be_true($tid);
		} else {
			return helper_common::be_false("应用程序发生错误，请稍候再试");
		}
	}
}