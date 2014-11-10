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

	public function topic($tid) {
		if (!is_numeric($tid)) {
			return helper_common::be_false("无效的话题ID");
		}
		$topic = $this->db->fetch_row("select * from topic where tid = ".$tid);
		if (!$topic) {
			return helper_common::be_false("无效的话题ID");
		}
		$topic['content'] = helper_common::array_stripslashes($topic['content']);
		$topic['user_info'] = $this->db->fetch_row("select * from member where `uid` = ".$topic['uid']);
		$topic['node_info'] = $this->db->fetch_row("select nodename,nodealias from node where `nid` = ".$topic['nid']);
		return helper_common::be_true($topic);
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

	public function topic_list_index($page) {
		//话题列表
		$page_size = 15;
		$start = $page*$page_size;
		$list = $this->db->fetch_all("select * from topic where `status` = 0 and `top` = 0 order by `postdate` desc limit $start, 15");
		foreach ($list as $key => $value) {
			$list[$key]['user_info'] = $this->db->fetch_row("select avatar,username from member where `uid` = ".$value['uid']);
			$list[$key]['node_info'] = $this->db->fetch_row("select nodename,nodealias from node where `nid` = ".$value['nid']);
		}
		return $list;
	}

	public function add_comment($request, $tid) {
		//添加评论

		/* user */
		$user = Yaf_Registry::get('_u');
		if (!$user) {
			return helper_common::be_false("请先登录");
		}

		if (empty(trim(strip_tags($request['comment'])))) return helper_common::be_false('请输入评论内容');

		$request = helper_common::array_addslashes($request);

		$data = array();
		$data['tid'] = $tid;
		$data['uid'] = $user['uid'];
		$data['username'] = $user['username'];
		$data['comment'] = $request['comment'];
		$data['postdate'] = time();

		if ($cid = $this->db->insert('comments', $data, true)) {
			return helper_common::be_true($cid);
		} else {
			return helper_common::be_false("应用程序发生错误，请稍候再试");
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
		elseif (empty(trim(strip_tags($request['content'])))) return helper_common::be_false('请输入内容');
		elseif (helper_common::mbstrlen(trim(strip_tags($request['content']))) < 15) return helper_common::be_false('内容少于15字');
		elseif (empty($nodelist[$request['node']])) return helper_common::be_false('无效的节点');

		/* content 内容过滤 */

		$request = helper_common::array_addslashes($request);

		$data = array();
		$data['nid'] = $request['node'];
		$data['uid'] = $user['uid'];
		$data['username'] = $user['username'];
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