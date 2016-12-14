<?php
namespace app\admin\model;
use \think\Model;
use \think\Db;

/**
* 
*/
class NavModel extends Model
{
	protected $table = 'tp_nav'; //数据库全名

	public function getAllNav($where=''){
		if( isset($where) ){
			return $this->order('sort','desc')->where($where)->select();
		}else{
			return $this->order('sort','desc')->select();
		}
		
	}

	public function addNav($data){
		return $this->insertGetId($data);
	}
	public function updateNav($where,$data){
		return $this->where($where)->update($data);
	}

	public function getVlaueNav($where,$value){
		return $this->where($where)->value($value);
	}

	public function delNav($where){
		return $this->where($where)->delete();
	}
	public function getOneNav($where){
		return $this->where($where)->find();
	}
	
}