<?php
namespace app\admin\model;
use \think\Model;
use \think\Db;


class CategoryModel extends Model
{
	protected $table = 'tp_category'; //数据库全名

	public function getAll($num = 20 ){
		$list =  $this->order(' sort DESC')->paginate($num);
		return $list;
	}

	public function getCate(){
		return $this->select();
	}

	public function editActe($where,$data){
		return $this->where($where)->update($data);
	}

	public function addCate($data){
		return $this->insertGetId($data);
	}

	public function delCate($where){
		return $this->where($where)->delete();
	}


}