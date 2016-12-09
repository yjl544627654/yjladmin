<?php
namespace app\home\controller;
use \think\Controller;  
use \think\Db; 
use \think\Request;
use \app\home\model\User;


class Login extends Controller
{

	
	function login(){
		$url = Request::instance();
		
		if ( $url->isPost()  ) {
			$user = $url->post('username');
			$pwd = $url->post('pwd');

			$userdb = new User;
			$ret = $userdb->CheckLogin($user,$pwd);
			if( $ret == true ){
				$_SESSION['user'] = $user;
				$_SESSION['is_login'] = true;
				$this->success('登录成功！',url('index/index') );
			}else{
				$this->assign('error','账号或者密码，请重新输入');
			}

		}
		return $this->fetch();
	}

	function out(){

		unset($_SESSION['user']);
		unset($_SESSION['is_login']);

	}
}