<?php
namespace app\home\model;
use \think\Model;
/**
* 
*/
class User extends Model
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
	
}