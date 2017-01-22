<?php
namespace app\admin;
use \think\Controller;
use \think\Db; 
use \think\Request;
use \think\Session;
use app\admin\model\SettingModel;

class Admin extends Controller
{	
	public $action;
	public $ctrl;
	private static $msgarr = null ;

	private $errorarr = [];

	function __construct()
	{
		parent::__construct();
		$request = Request::instance();

		$this->action = $action = strtolower( $request->action() );
		$this->ctrl = $ctrl = strtolower( $request->controller() );

		//检验登录状态
		if( Session::has('user')!=true && $ctrl!='login' && $action != 'login' ){
			header("Location: ".url('login/login'));
			exit;
		}

		//权限
		$user_power = Db::name('user')->where('id',Session::get('admin_id'))->value('power');
		$arr_power = explode(',', $user_power);
		$power_url = $ctrl.'/'.$action;

		if( !in_array($power_url , $arr_power) && $user_power != 'all' && Session::has('user')   ){
			header("Location: ".url('login/notpower'));
			exit;
		}


		//获取ico 图标
		$setting = SettingModel::getSetting('site_ico');


		//获取消息
		$this->checkflash('msg');
		//获取错误的消息
		$this->checkflash('error');
		Session::flush();

		$this->assign('setting',$setting);
		$this->assign('ctrl',$ctrl);
	}

	public function  res($res,$success=null,$error=null){
		if( isset($res) ){
			$this->setflash($success ? $success : '操作成功！');
		}else{
			$this->error($error ? $error : '操作失败！');
		}
	}

	public function validata($name ,$msg=null ,$url=null){
		$name = input('param.'.$name);
		if( !empty($name) ){
			return $name;
		}else{
			if( empty(self::$msgarr) ){
				self::$msgarr = $msg;
				//设置快闪
				Session::flash('error',$msg);
			}
		}
		if( $url != null ){
			//有url就重定url
			$this->redirect($url);
		}
	}

	//设置只显示一次的 消息
	// 删除时候记得添加$url=index
	//修改时候不用填写$url
	public function setflash($msg,$url=null ){
		Session::flash('msg',$msg);
		if( empty($url) ){
			$get = $_GET;
			$this->redirect($this->ctrl.'/'.$this->action,$get);
		}else{
			$this->redirect($url);
		}
	}

	//获取只显示一次的消息
	public  function getflash($name='msg'){
		$ret = Session::get($name);
		
		return $ret ;
	}

	public function checkflash($key ='error'){
		//获取error信息
		$msg = $this->getflash($key);
		if( !empty($msg) ){
			$this->assign($key,$msg);
			return false;
		}else{
			return true ;
		}
	}

	// public function errormsg($msg,$url=''){
	// 	Session::flash('error',$msg);
	// 	if( empty(self::$msgarr) ){
	// 		self::$msgarr = $msg;
	// 	}
	// 	if( empty($url) ){
	// 		$get = $_GET;
	// 		$this->redirect($this->ctrl.'/'.$this->action,$get);
	// 	}else{
	// 		$this->redirect($url);
	// 	}
	// }



}
