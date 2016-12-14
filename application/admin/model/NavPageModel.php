<?php
namespace app\admin\model;
use \think\Model;
use \think\Db;

/**
* 
*/
class NavPageModel extends Model
{
	protected $table = 'tp_nav_page'; //数据库全名

	public function getVlaueNavPage($where,$value){
		return $this->where($where)->value($value);
	}

	public function delNavPage($where){
		return $this->where($where)->delete();
	}

}