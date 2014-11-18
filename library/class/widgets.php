<?php

class class_widgets {

	private $db;

	function __construct() {
		//初始化数据库连接
		$this->db = Yaf_Registry::get('db');
	}

	/* 热门话题 */
	public function hot_topic() {
		if (!$hot_topic = Yaf_Registry::get('memcache')->get('jj_hot_topic')) {
			$hot_topic = $this->db->fetch_all("select * from `topic` where (".time()." - postdate) < 20592000  order by views desc,tid desc limit 5");
			Yaf_Registry::get('memcache')->set('jj_hot_topic', $hot_topic, MEMCACHE_COMPRESSED, 3600);
		}
		$html = '<ul class="list-unstyled">';
		foreach ($hot_topic as $key => $value) {
			$html .= '<li><a href="'.helper_common::get_uri('topic/'.$value['tid']).'">'.$value['title'].'</a></li>';
		}
		$html .= '</ul>';
		echo $html;
	}

	/* 话题分类 */
	public function node() {
		if (!$nodelist = Yaf_Registry::get('memcache')->get('jj_nodelist')) {
			$rs = $this->db->fetch_all("select * from `node` order by nid asc");
			$nodelist = array();
			foreach ($rs as $key => $value) {
				$nodelist[$value['nid']] = $value;
			}
			Yaf_Registry::get('memcache')->set('jj_nodelist', $nodelist, MEMCACHE_COMPRESSED, 0);
		}
		$html = '';
		foreach ($nodelist as $key => $value) {
			$html .= '<a href="'.helper_common::get_uri('node/'.$value['nodealias']).'" class="btn btn-default btn-node" role="button">'.$value['nodename'].'</a>';
		}
		echo $html;
	}
}