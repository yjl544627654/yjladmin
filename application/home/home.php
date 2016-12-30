<?php
namespace app\home;
use \think\Controller;
use \app\admin\model\NavModel;

class Home extends Controller
{
	
	function __construct()
	{
		parent::__construct();

		//查询导航
		
		$navList = NavModel::where('nav_id','0')->order('sort',"desc")->select();

		$this->assign('navlist',$navList);
	}
}
