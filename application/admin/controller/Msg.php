<?php
namespace app\admin\controller;
use \app\admin\admin;
use \think\Db; 
use \think\Request;
use \app\admin\model\MsgModel;

class Msg extends Admin
{

	public function index(){

		$db = new MsgModel;

		$list = $db->paginate(20);
		$this->assign('list',$list);
		return $this->fetch();
	}

	public function del(){

		$db = new MsgModel;

		$map['id'] = input('get.id') ? input('get.id') : $this->error('获取id失败');

		$this->res($db->where($map)->delete());

	}
}