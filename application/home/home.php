<?php
namespace app\home;
use \think\Controller;
use \app\admin\model\NavModel;
use app\admin\model\SettingModel;
use Think\Request;

class Home extends Controller
{
	
	function __construct()
	{
		parent::__construct();

		//缓存
		$options = [
		     // 缓存类型为File
		    'type'   => 'File', 
		     // 缓存有效期为永久有效
		    'expire' => 0,
		     // 指定缓存目录
		    'path'   => APP_PATH . 'runtime/cache/', 
		];

		// 缓存初始化
		// 不进行缓存初始化的话，默认使用配置文件中的缓存配置
		cache($options);

		$request  = Request::instance();
		$ctrl = strtolower($request ->controller());
		$action = strtolower($request->action());
		$getarr = input('get.');
		foreach ($getarr as $k => $v) {
			$str .= "&{$k}={$v}";
		}
		get_cache($ctrl.'/'.$action.$str);  //获取缓存

		$navList = NavModel::where('nav_id','0')->order('sort',"desc")->select();
		//查询导航
		$logo = SettingModel::getSetting('site_logo');  //网站logo
		$ico = SettingModel::getSetting('site_ico'); 	//网站ico
		$title = SettingModel::getSetting('site_title');  //网站标题
		$key = SettingModel::getSetting('site_key');   	//网站关键字
		$describe = SettingModel::getSetting('site_describe'); //网站描述
		$copyright= SettingModel::getSetting('site_copyright');  //版权信息
		$record = SettingModel::getSetting('site_record');  	//网站备案号

		$this->assign('site_copyright',$copyright['site_copyright']);
		$this->assign('site_record',$record['site_record']);
		$this->assign('site_describe',$describe['site_describe']);
		$this->assign('site_key',$key['site_key']);
		$this->assign('site_title',$title['site_title']);
		$this->assign('site_ico',$ico['site_ico']);
		$this->assign('site_logo',$logo['site_logo']);
		$this->assign('navlist',$navList);

		

	}

	public function validata($name ,$msg=null){
		$name = input('param.'.$name);
		if( !empty($name) ){
			return $name;
		}else{
			$this->error($msg ? $msg : '发生错误');
		}
	} 
}
