<?php
namespace app\admin\model;
use \think\Model;
use \think\Db;


class UserGroupModel extends Model
{
	
	protected $table = 'tp_user_group'; //数据库全名


	public function getGroup(){
		$list = $this->field('groupname,id')->select();
		return $list;
	}
}