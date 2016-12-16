<?php
namespace app\admin\model;
use \think\Model;
use \think\Db;

class SettingModel extends Model
{
	
	protected $table = 'tp_setting'; //数据库全名

	static $setting = [] ; 

	static function getSetting($value){
		if( !isset(self::$setting[$value]) ){
			//获取setting表保存的值
			self::$setting[$value] = self::getFirst($value);
		}
		return self::$setting;
	}

	static function getFirst($key){
		return DB::name('setting')->where('key',$key)->value('value');
	}

	static function updateSetting($value)
	{
		//修改之后
		self::$setting[$value] = self::getFirst($value);
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