<?php

class Bootstrap extends Yaf_Bootstrap_Abstract{

	private $config;

	public function _initConfig() {
		$this->config = Yaf_Application::app()->getConfig();
		//注册配置对象到config，可以全局使用
		Yaf_Registry::set('config', $this->config);
	}

	//配置是否报错
    public function _initError(Yaf_Dispatcher $dispatcher) {
        if ($this->config->customer->debug) {
            ini_set('display_errors', 'On');
        } else {
            ini_set('display_errors', 'Off');
        }
    }

    //过滤输入
    public function _initEnv(Yaf_Dispatcher $dispatcher) {
        if (function_exists('get_magic_quotes_gpc') && @get_magic_quotes_gpc()) {
            $_GET = helper_common::array_stripslashes($_GET);
            $_POST = helper_common::array_stripslashes($_POST);
            $_COOKIE = helper_common::array_stripslashes($_COOKIE);
        }
    }

    //配置路由
	public function _initRoute(Yaf_Dispatcher $dispatcher) {
        $routes = $this->config->routes;
        if (!empty($routes)) {
            $router = $dispatcher->getRouter();
            $router->addConfig($routes);
        }
	}

	//配置memcache，注册memcache全局变量
	public function _initMemcache(Yaf_Dispatcher $dispatcher) {
		$memcache_config = $this->config->memcache;
		if (!empty($memcache_config)) {
			if (class_exists('memcache')) {
				$memcache_client = new memcache();
				//向连接池中添加一个memcache服务器
				$re = $memcache_client->addServer($memcache_config->host, $memcache_config->port);
				if (!$re) {
					exit('Can not connect memcache server');
				}
				Yaf_Registry::set('memcache', $memcache_client);
			}
		}
	}

	//配置会话
	public function _initSession(Yaf_Dispatcher $dispatcher) {
        if (!empty($this->config->session)) {
            $session = $this->config->session;
            session_set_cookie_params(
                $session->cookie_lifetime,
                $session->cookie_path, 
                $session->cookie_domain
            );
            session_name($session->name);
            if(!empty($session->save_handler)) {
                switch($session->save_handler) {
                    case 'memcache':
                        session_module_name('memcache');
                        session_save_path($session->save_path);
                        session_cache_expire($session->expire/60);
                        break;
                    case 'files':
                        if($session->save_path) {
                            if(!file_exists($session->save_path)) {
                                mkdir($session->save_path, true);
                                if(!file_exists($session->save_path)) {
                                    exit('session_path is not exits');
                                }
                            }
                            session_module_name('files');
                            session_save_path($session->save_path);
                            session_cache_expire($session->expire/60);
                        }
                        break;
                    default:
                        break;
                }
            }
        }
        Yaf_Session::getInstance()->start();
    }

	public function _initDatabase(Yaf_Dispatcher $dispatcher) {
        $db = db_mysqli::getInstance($this->config->db->toArray());
        Yaf_Registry::set('db', $db);
	}

    public function _initUser(Yaf_Dispatcher $dispatcher) {
        $session = Yaf_Session::getInstance();
        $user_model = new UserModel();
        $user = $user_model->auth();
    }
}