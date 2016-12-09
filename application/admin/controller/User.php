<?php
namespace app\admin\controller;
use \app\admin\admin;
use \think\Db; 
use \app\admin\model\UserModel;


class User extends Admin
{
	
	function index(){
		
		$user_db = new UserModel;

		$list = $user_db->getList();
		
		$page = $list->render();
		
		$this->assign('page',$page);
		$this->assign('list',$list);
		return $this->fetch();
	}

	function add(){

		return $this->fetch();
	}

	function edit(){

		$id = input('?get.id') ? input('get.id') : $this->error('参数错误');
		$user_db = new UserModel;

		//var_dump(input('?post'));exit;
		if( request()->isPost() ){ 
			//提交post
			var_dump($_POST);exit;
			
		}else{

			$show = $user_db->getOneUser($id);

			$this->assign('user',$show);

			return $this->fetch();
		}
		
		
	}

	function del(){

	}
}