<?php
namespace app\admin;
use \think\Controller;
use \think\Db; 
use \think\Request;
use \think\Session;

class Admin extends Controller
{
	
	function __construct()
	{
		parent::__construct();

		$request  = Request::instance();
		$ctrl = strtolower($request ->controller());
		$action = strtolower($request->action());

		//var_dump(Session::get('user'));exit;

		if( Session::has('user')!=true && $ctrl!='login' && $action != 'login' ){
			header("Location: ".url('login/login'));
			exit;
		}
	}

	public function  res($res,$success=null,$error=null){
		if( isset($res) ){
			$this->success($success ? $success : '操作成功！');
		}else{
			$this->error($success ? $success : '操作失败！');
		}
	}

	public function validation($name ,$msg=null){
		$name = input('param.'.$name);
		if( !empty($name) ){
			return $name;
		}else{
			$this->error($msg ? $msg : '发生错误');
		}
	}
}
