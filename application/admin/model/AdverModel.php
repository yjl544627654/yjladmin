<?php
namespace app\admin\model;
use \think\Model;
use \think\Db;


class AdverModel extends Model
{

	protected $table = 'tp_advertising'; //数据库全名

	function getAll($num=10){
		return $this->order('addtime','DESC')->paginate($num);
	}

	function getOne($where){
		return $this->where($where)->find();
	}

	
	function addAdver($data){
		return $this->insertGetId($data);
	}

	function updateAdver($where,$data){
		return $this->where($where)->update($data);
	}

	function delAdver($where){
		return $this->where($where)->delete();
	}

	function getBanner( $where ){
		return $this->where($where)->select();
	}
}