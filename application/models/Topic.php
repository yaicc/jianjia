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

	public function topic($tid, $page) {
		if (!is_numeric($tid) || $tid < 1 || !is_numeric($page) || $page < 1) {
			return helper_common::be_false("无效的话题");
		}
		$topic = $this->db->fetch_row("select * from topic where tid = ".$tid);
		if (!$topic) {
			return helper_common::be_false("无效的话题ID");
		}
		$topic['content'] = helper_common::array_stripslashes($topic['content']);
		$topic['user_info'] = $this->db->fetch_row("select * from member where `uid` = ".$topic['uid']);
		$topic['node_info'] = $this->db->fetch_row("select nodename,nodealias from node where `nid` = ".$topic['nid']);
		$tmp = $this->db->fetch_row("select uid,username,postdate from comments where `tid` = ".$topic['tid']." order by postdate desc limit 1");
		$topic['comment_info'] = $tmp ? $tmp : array('counts' => 0, 'msg' => '暂无回复');
		$topic['comment_info']['counts'] = $this->db->fetch_field("select count(cid) as counts from comments where `tid` = ".$topic['tid']);

		$page_size = 15;
		$start = ($page-1)*$page_size;
		$topic['comments'] = $this->db->fetch_all("select *, avatar from comments,member where comments.uid = member.uid and `tid` = ".$topic['tid']." order by `postdate` desc limit $start, $page_size");
		foreach ($topic['comments'] as $key => $value) {
			if ($value['reply'] != 0) {
				$topic['comments'][$key]['reply_info'] = $this->db->fetch_row("select comment,uid,username,status from comments where cid = ".$value['reply']);
			}
			$topic['comments'][$key]['floor'] = $topic['comment_info']['counts'] - $start - $key;
		}
		/* 更新 */
		$this->db->update('topic', array('views' => $topic['views'] + 1 ), array('tid' => $topic['tid'] ));
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

	public function topic_total($node = 0) {
		if ($node == 0) {
			return $this->db->fetch_field("select count(tid) from topic where `status` = 0 and `top` = 0");
		} else {
			return $this->db->fetch_field("select count(tid) from topic where `status` = 0 and `top` = 0 and nid = $node");
		}
	}

	public function topic_list_index($page) {
		//话题列表
		if (!is_numeric($page) || $page < 1) {
			return helper_common::be_false('无效的page');
		}
		$page_size = 15;
		$start = ($page-1)*$page_size;
		$list = $this->db->fetch_all("select tid,nid,uid,username,title,top,rate,postdate from topic where `status` = 0 and `top` = 0 order by `postdate` desc limit $start, 15");
		if (!$list) {
			return helper_common::be_false('没有话题了');
		}
		foreach ($list as $key => $value) {
			$list[$key]['user_info'] = $this->db->fetch_row("select avatar,username from member where `uid` = ".$value['uid']);
			$list[$key]['node_info'] = $this->db->fetch_row("select nodename,nodealias from node where `nid` = ".$value['nid']);
			$tmp = $this->db->fetch_row("select uid,username from comments where `tid` = ".$value['tid']." order by postdate desc limit 1");
			$list[$key]['comment_info'] =  $tmp ? $tmp : array('counts' => 0, 'msg' => '暂无回复');
			$list[$key]['comment_info']['counts'] = $this->db->fetch_field("select count(cid) as counts from comments where `tid` = ".$value['tid']);
		}
		return helper_common::be_true($list);
	}

	public function add_comment($request, $tid) {
		//添加评论

		$data = array();

		/* user */
		$user = Yaf_Registry::get('_u');
		if (!$user) {
			return helper_common::be_false("请先登录");
		}

		if (empty(trim(strip_tags($request['comment'])))) return helper_common::be_false('请输入评论内容');

		$topic = $this->db->fetch_row("select * from topic where tid = ".$tid);
		if (!$topic) {
			return helper_common::be_false("无效的话题ID");
		}

		if ($request['reply']) {
			if (!is_numeric($request['reply']) || !$this->db->fetch_row("select cid from comments where cid = ".$request['reply'])) {
				return helper_common::be_false("无效的回复ID");
			}
			$data['reply'] = $request['reply'];
		}

		$request = helper_common::array_addslashes($request);
		
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