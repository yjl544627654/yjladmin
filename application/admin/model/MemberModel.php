<?php
namespace app\admin\model;
use \think\Model;
use \think\Db;


class MemberModel extends Model
{
	protected $table = 'tp_members'; //数据库全名

	public function CheckMembersName($name){
		$username = $this->where('username',$name)->value('username');
		if( !empty($username) ){
			return $username;
		}else{
			return false;
		}
	}

	public function getMembersHash($id){
		return $this->where('id',$id)->value('hash');
	}
}