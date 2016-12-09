<?php
namespace app\admin\controller;
use \app\admin\admin;
use \think\Db; 
use \think\Request;

class Index extends Admin
{
    public function index()
    {
        $version = DB::query('select version() as ver');

        $data['yjladmin'] = 'v1.0';
        $data['Thinkphp_version'] =  THINK_VERSION;
        $data['system'] = php_uname('s');
        $data['php_edition'] = PHP_VERSION;
        $data['mysql_edition'] = $version[0]['ver'];
        $data['server'] = $_SERVER['SERVER_SOFTWARE'];
        $data['http'] =  $_SERVER["HTTP_HOST"];

        //dump($data);exit;

        $this->assign('data',$data);
        return $this->fetch();
    }

    public function test(){
    	
        //查询一列字段  返回一维数组
        //$ret = Db::name('user')->where('uid','2')->column('username');

        //查询一组数据  返回一维数组
        $ret = Db::name('user')->where('uid','2')->find();
        
        //查询一个字段  返回一个值
        //$ret = Db::name('user')->where('uid','2')->value('username');

        //查询所有数据 返回二维数据
        //$ret = Db::name('user')->where('uid','2')->select();

        //添加数据
        //$ret = Db::name('log')->insert(['admin'=>'test','operate'=>'测试插入数据222']);
        //获取刚刚插入的主键值
        //$getid = Db::name('log')->getLastInsID();

        //添加数据并返回 主键值
        //$ret = Db::name('log')->insertGetId(['admin'=>'test','operate'=>'测试插入数据222']);

        //更新数据
        //$ret = DB::name('log')->where('id','181')->update(['addtime'=>'110','mark'=>'test']);

        //删除数据
        //$ret = DB::name('log')->where('id','181')->delete();


        dump($ret);
        
        $url = Request::instance();
    	if( !empty($_GET) ){
    		var_dump($url->get('user'));
    	}else{
            $this->assign('name','thinkpppp');
            return $this->fetch();
        }
		
    }
}
