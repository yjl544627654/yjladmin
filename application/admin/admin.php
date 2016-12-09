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
}
