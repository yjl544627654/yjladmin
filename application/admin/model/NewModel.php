<?php
namespace app\admin\model;
use \think\Model;
use \think\Db;
use \think\Validate;


class NewModel extends Model
{
	protected $table = 'tp_new'; //数据库全名

	//验证 
	protected $rule = [
		'title'=>'require|notnull',
		'e_title'=>'require',
		'cate_id'=>'require',
		'content'=>'require',

	];
	protected $msg =[
		'title.require'=>'请填写标题',
		'e_title.require'=>'请填写摘要',
		'cate_id.require'=>'请选择分类',
		'content.require'=>'填写内容',
		'content.notnull'=>'填写内容',
	];

	
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

	public function updateNew($where,$data){
		return $this->where($where)->update($data);
	}

	public function delNew($where){
		return $this->where($where)->delete();
	}


}