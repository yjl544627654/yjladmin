<?php
namespace app\admin\model;
use \think\Model;
use \think\Db;


class CategoryModel extends Model
{
	protected $table = 'tp_category'; //数据库全名

	public function getAll($num = 20){
		$list =  $this->paginate($num);
		return $list;
	}

	public function getCate(){
		return $this->select();
	}


}