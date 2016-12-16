<?php
namespace app\admin\controller;
use \app\admin\admin;
use \think\Db; 
use \think\Request;
use \app\admin\model\ProductModel;

class Product extends Admin
{

	public function index(){

		$db = new ProductModel;

		$list = $db->getAll();

		$page = $list->render();
		$this->assign('page',$page);
		$this->assign('list' , $list);
		return $this->fetch();
	}

	function add(){
		$db = new ProductModel;

		if( request()->isPost() ){

			if( input('post.is_index') == 1 ){
				$data['is_index'] = 1;
				$data['p_id'] = 0;
			}else{
				$data['is_index'] = 0;
				$data['p_id'] = $this->validata('uid','请选择相册分类');
			}
			$data['name'] = $this->validata('name','请填写相册名');
			$data['img'] = $this->validata('img','请添加图片');
			$data['sort'] = input('?post.sort') ? input('post.sort') :0 ;
			$data['content'] = input('post.content');
			$data['addtime'] = time();

			$db->addData($data);

			$this->success('新增成功', 'Product/index');
		}else{

			$Product_index = $db->getProductIndex();

			$this->assign('index',$Product_index);
			return $this->fetch();
		}
		
	}

	function edit(){
		$db = new ProductModel;
		$id = input('get.id') ? input('get.id') : $this->error('参数错误');
		$map['id'] =$id;

		if( request()->isPost() ){

			if( input('type') == 1 ){
				$data['is_index'] = 1;
				$data['p_id'] = 0;
			}else{
				$data['is_index'] = 0;
				$data['p_id'] = $this->validata('uid','请选择相册分类');
			}
			$data['name'] = $this->validata('name','请填写相册名');
			$data['img'] = $this->validata('img','请添加图片');
			$data['sort'] = input('?post.sort') ? input('post.sort') :0 ;
			$data['content'] = input('post.content');
			

			$this->$success('修改成功！');			
		}else{

			$Product_index = $db->getProductIndex();
			$show = $db->getOne($map);
			$this->assign('index',$Product_index);
			$this->assign('show',$show);
			return $this->fetch();
		}
	}
	function del(){
		$db = new ProductModel;

	}

	function product(){
		return $this->fetch();
	}

	function ajax_uploadimg(){
		$set_db = new ProductModel;

		$file = request()->file('files');
		$path = '/public/assets/img/product/';
		$info = $file->validate(['size'=>102400,'ext'=>'jpg,png,gif,ico'])->move(ROOT_PATH . $path);
		if($info){
			$img_path = $info->getSaveName();
		}else{
			//上传失败 返回失败信息
			$msg = $file->getError();
		}

		if( !isset($msg) ){
			$res['status'] = 1;
			$res['img_path'] = $img_path;
			return $res;
		}else{
			$res['status'] = 0;
			$res['msg'] = $msg;
			return $res;
		}

	}
}