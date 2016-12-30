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

	
	public function getNew($where='',$num = 20){
		$list =  $this->where($where)->field('id,sort,title,auth,addtime,cate_id')->paginate($num);
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


	//下面是提供给前端的方法
	public function getIndexListNew($where='',$order='',$limit=''){

		$order = $order ? $order : 'addtime desc' ; 
		$limit = $limit ? $limit : 5;

		$list = $this->where($where)->order($order)->limit($limit)->select();
		return $list;
	}


}