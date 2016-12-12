<?php
namespace app\admin\model;
use \think\Model;
use \think\Db;


class NewModel extends Model
{
	protected $table = 'tp_new'; //数据库全名

	public function getNew($num = 20){
		$list =  $this->paginate($num);
		return $list;
	}

	public function addNews($data){
		return $this->insertGetId($data);
	}

	public function getOneNew($id){
		return $this->where('id',$id)->find();
	}


}