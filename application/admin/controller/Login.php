<?php
namespace app\admin\controller;
use \app\admin\admin;
use \think\Db; 
use \think\Request;
use \app\admin\model\UserModel;
use \think\Session;

class Login extends Admin
{

	function login(){
		$url = Request::instance();
		
		if( Session::has('user') != true ){

			if ( $url->isPost()  ) {
				$user = $url->post('username');
				$pwd = $url->post('pwd');

				$userdb = new Usermodel;
				$ret = $userdb->CheckLogin($user,$pwd);
				if( $ret == true ){

					$img = $userdb->getUserImg($user);

					Session::set('user',$user);
					Session::set('is_login',true);
					Session::set('img',$img);
					//var_dump(Session::has('user'));exit;

					$this->success('登录成功！',url('index/index') );
				}else{
					
					$this->assign('user' , $user);
					$this->assign('error','账号或者密码，请重新输入');
				}

			}
			return $this->fetch();

		}else{
			$this->success('你已登录，请勿重复登录',url('index/index') );
		}
		
	}

	function out(){

		Session::delete('user');
		Session::delete('is_login');
		Session::delete('img');

		$this->success('退出成功！',url('index/index') );
	}
}