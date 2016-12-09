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

	function getList(){
		$list =  $this->paginate(2);
		return $list;
	}

	function getOneUser($id){
		//return DB::table($this->table)->where('id',$id)->find();
		return $this->where('id',$id)->find()->toArray();
	}
	
}