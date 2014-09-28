<?php

class db_mysqli {

    protected static $_instance;

	var $curlink;
	var $config = array();
	var $_lastsql;

    public static function getInstance($config = array()) {
        if(!is_object(self::$_instance)) {
            self::$_instance = new self($config);
        }
        return self::$_instance;
    }

	/**
     * 构造函数
     * @param array $config 配置数组
     */
	function __construct($config = array()) {

		$this->config = $config;

		if(empty($this->config)) {
			$this->halt('config_db_not_found');
		}

		$this->curlink = $this->_dbconnect(
			$this->config['host'],
			$this->config['user'],
			$this->config['password'],
			$this->config['charset'],
			$this->config['dbname']
		);
	}

	/**
     * 链接数据库
     * @param string $dbhost 服务器
     * @param string $dbuser 用户名
     * @param string $dbpw 密码
     * @param string $dbcharset 字符集
     * @param string $dbname 数据库名
     * @return db_link
     */
	private function _dbconnect($dbhost, $dbuser, $dbpw, $dbcharset, $dbname, $halt = true) {

		$link = new mysqli($dbhost, $dbuser, $dbpw, $dbname);
        if ($link->connect_errno) {
            $halt && $this->halt('数据库连接失败');
        }
        $link->set_charset($dbcharset);
        return $link;
	}

	/**
     * 查询
     * @param string $sql 要查询的sql
     * @return bool|objeact 查询结果
     */
    public function query($sql) {
    	$this->_lastsql = $sql;
    	if(!($query = $this->curlink->query($sql))) {
			$this->halt('errno '.$this->errno().' : '.$this->error());
		}
		return $query;
    }

    /**
     * 查询结果集
     * @param string $sql 要查询的sql
     * @return array 查询结果
     */
    public function fetch_all($sql) {
    	$res = $this->query($sql);
    	$data = $res->fetch_all(MYSQLI_ASSOC);
    	$this->free_result($res);
    	return $data;
    }

    /**
     * 查询结果集, 单行
     * @param string $sql 要查询的sql
     * @return array 查询结果
     */
    public function fetch_row($sql) {
    	$res = $this->query($sql);
		$ret = $res->fetch_array(MYSQLI_ASSOC);
		$this->free_result($res);
		return $ret;
    }

    /**
     * 查询结果集, 字段
     * @param string $sql 要查询的sql
     * @return string 查询结果
     */
    public function fetch_field($sql) {
    	$res = $this->query($sql);
        $ret = $res->fetch_row();
        $this->free_result($res);
		return $ret ? $ret[0] : false;
    }

    /**
     * 插入
     * @param string $table 表名
     * @param array $data 插入数据(字段名=>字段值)
     * @param bool $return_id 是否返回插入ID
     * @return bool|string 查询结果
     */
    public function insert($table, $data = array(), $return_id = false) {
    	$sql = $this->implode($data);
    	$res = $this->query("INSERT INTO $table SET $sql");
    	if($res && $return_id) {
            $id = $this->curlink->insert_id;
            $this->free_result($res);
            return $id;
        }
    	else return $res;
    }

    /**
     * 更新
     * @param string $table 表名
     * @param array $data 待更新数据(字段名=>字段值)
     * @param string|array $condition 条件
     * @return bool 查询结果
     */
    public function update($table, $data = array(), $condition) {
    	$sql = $this->implode($data);
    	$where = '';
		if (empty($condition)) {
			$where = '1';
		} elseif (is_array($condition)) {
			$where = $this->implode($condition, ' AND ');
		} else {
			$where = $condition;
		}
    	return $this->query("UPDATE $table SET $sql WHERE $where");
    }

    /**
     * 删除
     * @param string $table 表名
     * @param string|array $condition 条件
     * @return bool 查询结果
     */
    public function delete($table, $condition) {
    	if (empty($condition)) {
    		$this->halt("危险操作：没有设置删除条件");
			return false;
		} elseif (is_array($condition)) {
			$where = $this->implode($condition, ' AND ');
		} else {
			$where = $condition;
		}
		return $this->query("DELETE FROM $table WHERE $where ");
    }

	/**
     * 释放结果内存
     * @param $query 数据指针
     * @return bool
     */
	private function free_result($query) {
		return $query ? $query->free() : false;
	}

	/**
     * 组装sql
     * @param array $array 数组
     * @return string
     */
	private function implode($array = array(), $glue = ',') {
		$sql = $comma = '';
		$glue = ' ' . trim($glue) . ' ';
		foreach ($array as $k => $v) {
			$sql .= $comma . '`'.$k.'`' . '=' . '\'' . addcslashes($v, "\n\r\\'\"\032") . '\'';
			$comma = $glue;
		}
		return $sql;
	}

	/**
     * 获取错误
     * @return string 错误信息
     */
    private function error() {
		return (($this->curlink) ? $this->curlink->error : mysqli_error());
	}

	/**
     * 获取错误码
     * @return int 错误码
     */
	private function errno() {
		return intval(($this->curlink) ? $this->curlink->errno : mysqli_errno());
	}

	/**
     * 打印错误信息
     * @param string 错误信息
     */
	private function halt($message) {
		helper_common::system_error($message);
	}
}