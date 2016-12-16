<?php
namespace app\admin\model;
use \think\Model;
use \think\Db;


class ProductModel extends Model
{
	protected $table = 'tp_product'; //数据库全名

	function getAll($num=''){
		return $this->where('is_index',1)->order('sort','DESC')->paginate($num);
	}

	function getProductIndex(){
		return $this->where('p_id','0')->field('id,name')->select();
	}

	function addData($data){
		return $this->insert($data);
	}
	function getOne($where){
		return $this->where($where)->find();
	}

	
}