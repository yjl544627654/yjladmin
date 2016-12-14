<?php
namespace app\admin\model;
use \think\Model;
use \think\Db;

/**
* 
*/
class SettingModel extends Model
{
	
	protected $table = 'tp_setting'; //数据库全名

	public function getFirst($key){
		return $this->where('key',$key)->value($key);
	}

	public function getAll(){
		return $this->select();
	}

	public function updateSetAll($data){

		foreach( $data as $k=>$v ){
			$arr['value'] = $v;
			$this->where('key',$k)->update($arr);
		}
		return true;
	}

	public function updateOne($where,$data){
		return $this->where($where)->update($data);
	}

}