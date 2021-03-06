<?php
namespace app\admin\controller;
use \app\admin\admin;
use \think\Db; 
use \app\admin\model\NewModel;
use \app\admin\model\CategoryModel;
use \think\Session;

class News extends Admin
{
	
	public function index(){

		$new_db = new NewModel;
		$cate_db = new CategoryModel;
		$title = input('get.title');
		if( !empty(input('get.cate')) ){
			$where['cate_id'] = input('get.cate');
		}
		if( isset($title) ){
			$where['title'] = ['like',"%".$title."%"] ;
		}
		$list = $new_db->getNew($where);
		$cate = $cate_db->getCate();

		$this->assign('cate',$cate);
		$this->assign('list',$list);
		$this->assign('page',$list->render());
		return $this->fetch();
	}


	public function add(){

		$cate_db = new CategoryModel;
		$new_db = new NewModel;

		if( request()->isPost() ){
				
			$data['title'] = $this->validata('title','请填写标题');
			$data['e_title'] = $this->validata('e_title','请填写摘要');
			$data['cate_id'] = $this->validata('uid','请选择分类');
			$data['content'] = $this->validata('editorValue','请填写内容');

			if( $this->checkflash() ){
				$data['img'] = input('post.img');
				$data['addtime'] = time();
				$data['auth'] = input('post.auth') ? input('post.auth') : Session::get('user') ;
				$data['sort'] = input('post.sort') ? input('post.sort') : 0;
				$res = $new_db->addNews($data);
				$res ? $this->setflash('添加文章成功！'):$this->errorflash('添加文章失败！');
			}
		}

		$cate = $cate_db->getCate();
		$this->assign('arr',input('post.') );
		$this->assign('cate',$cate);
		return $this->fetch();
		
	}

	public function ajax_uploadimg(){
		$db = new NewModel;

		$file = request()->file('files');
		$path = 'public/assets/img/new/';
		$info = $file->validate(['size'=>612400,'ext'=>'jpg,png,gif,ico'])->move(ROOT_PATH . $path);
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

	public function edit(){

		$id = input('?get.id') ? input('get.id') : $this->error('参数错误');
		$new_db = new NewModel;
		$cate_db = new CategoryModel;

		if( request()->isPost() ){
			$data['title'] = $this->validata('title','请填写标题');
			$data['e_title'] = $this->validata('e_title','请填写摘要');
			$data['cate_id'] = $this->validata('uid','请选择分类');
			$data['content'] = $this->validata('editorValue','填写内容');

			if( $this->checkflash() ){
				$data['img'] = input('post.img');
				$data['updatetime'] = time();
				$data['auth'] = input('post.auth') ? input('post.auth') : Session::get('user') ;
				$data['sort'] = input('post.sort') ? input('post.sort') : 0;

				$map['id'] = $id;
				$new_db->updateNew($map,$data);
				$this->setflash('修改文章成功！');
			}
		}

		$show = $new_db->getOneNew($id)->toArray();
		
		$cate = $cate_db->getCate();
		$this->assign('cate',$cate);
		$this->assign('show',$show);
		return $this->fetch();
		
	}

	public function del(){
		$new_db = new NewModel;
		$id = input('?get.id') ? input('get.id') : $this->error('参数错误');
		$map['id'] = $id;
		$res = $new_db->delNew($map) ;
		$res ? $this->setflash('删除文章成功！','index') : $this->errorflash('删除文章失败！');

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
		$data['cate_name'] = $this->validata('cate','请输入分类名','cate');
		$data['sort'] = input('?post.sort')?input('post.sort'):0;
		$data['addtime'] = time();
		$res = $cate_db->addCate($data);
	}

	public function cate_del(){
		$cate_db = new CategoryModel;
		$id = input('?get.id') ? input('get.id') : $this->error('参数错误');
		$where['id'] = $id;
		$this->res($cate_db->delCate($where));
	}
}