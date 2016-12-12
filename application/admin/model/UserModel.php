<?php
namespace app\admin\model;
use \think\Model;
use \think\Db; 
/**
* 
*/
class UserModel extends Model
{
	protected $table = 'tp_user'; //数据库全名

	function CheckLogin($user,$pwd){

		if( !empty($user) && !empty($pwd)  ){
			$hash = $this->where('username',$user)->value('hash');
			$md5pwd = $this->where('username',$user)->value('pwd');

			if( md5($pwd.$hash) == $md5pwd ){
				return true;
			}else{
				return false;
			}
		}
		
	}

	function getList($num = 20){
		$list =  $this->paginate($num);
		return $list;
	}

	function getOneUser($id){
		return $this->where('id',$id)->find()->toArray();
	}

	public function editUser($data,$where){
		return $this->save($data,$where);
	}

	public function getUserImg($user){
		return $this->where('username',$user)->value('img');
	}

	public function getUserHash($id){
		return $this->where('id',$id)->value('hash');
	}

	public function CheckUserName($name){
		$username = $this->where('username',$name)->value('username');
		if( !empty($username) ){
			return $username;
		}else{
			return false;
		}
	}
	public function addUser($data){
		return $this->insertGetId($data);
	}

	public function delUser($id){
		return $this->where('id',$id)->delete($id);
	}
	
}