<?php
namespace app\admin\controller;
use \app\admin\admin;
use \think\Db; 
use \app\admin\model\NewModel;
use \app\admin\model\CategoryModel;


class News extends Admin
{
	
	public function index(){

		$new_db = new NewModel;
		$list = $new_db->getNew();
		$cate_db = new CategoryModel;

		$cate = $cate_db->getCate();

		$this->assign('cate',$cate);
		$this->assign('list',$list);
		return $this->fetch();
	}


	public function add(){

		$cate_db = new CategoryModel;
		$new_db = new NewModel;

		if( request()->isPost() ){
			
			$data['title'] = $this->validata('title','请填写标题');
			$data['e_title'] = $this->validata('e_title','请填写摘要');
			$data['cate_id'] = $this->validata('uid','请选择分类');
			$data['content'] = $this->validata('editorValue','填写内容');
			$data['addtime'] = time();
			$this->res($new_db->addNews($data));

		}else{
			$cate = $cate_db->getCate();
			$this->assign('cate',$cate);
			return $this->fetch();
		}
		
	}

	public function edit(){

		$id = input('?get.id') ? input('get.id') : $this->error('参数错误');
		$new_db = new NewModel;
		$cate_db = new CategoryModel;

		if( request()->isPost() ){
			$data['title'] = $this->validata('title','请填写标题');
			$data['e_title'] = $this->validata('e_title','请填写摘要');
			$data['cate_id'] = $this->validata('uid','请选择分类');
			$data['content'] = $this->validata('editorValue','填写内容');
			$data['updatetime'] = time();

			$map['id'] = $id;
			$this->res($new_db->updateNew($map,$data));

		}else{
			$show = $new_db->getOneNew($id)->toArray();
			
			$cate = $cate_db->getCate();
			$this->assign('cate',$cate);
			$this->assign('show',$show);
			return $this->fetch();
		}
	}

	public function del(){
		$new_db = new NewModel;
		$id = input('?get.id') ? input('get.id') : $this->error('参数错误');
		$map['id'] = $id;
		$this->res( $new_db->delNew($map));
	}

	public function cate(){
		$cate_db = new CategoryModel;
		$list = $cate_db->getAll();
		$this->assign('list',$list);
		return $this->fetch();
	}

	public function cate_edit(){
		$cate_db = new CategoryModel;
		$id = input('?get.id') ? input('get.id') : $this->error('参数错误');
		$map['id'] =$id;
		$data['cate_name'] = $this->validata('cate_name','请输入分类');
		$data['sort'] = input('?post.sort')?input('post.sort'):0;
		$ret = $cate_db->editActe($map,$data);
		$this->res($ret);
	}

	public function cate_add(){
		$cate_db = new CategoryModel;
		$data['cate_name'] = $this->validata('cate_name','请输入分类名');
		$data['sort'] = input('?post.sort')?input('post.sort'):0;
		$data['addtime'] = time();
		$this->res($cate_db->addCate($data));
	}

	public function cate_del(){
		$cate_db = new CategoryModel;
		$id = input('?get.id') ? input('get.id') : $this->error('参数错误');
		$where['id'] = $id;
		$this->res($cate_db->delCate($where));
	}
}