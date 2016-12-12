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
			
			$data['title'] = $this->validation('title','请填写标题');
			$data['e_title'] = $this->validation('e_title','请填写摘要');
			$data['cate_id'] = $this->validation('uid','请选择分类');
			$data['content'] = $this->validation('editorValue','填写内容');

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


		}else{
			$show = $new_db->getOneNew($id)->toArray();
			
			$cate = $cate_db->getCate();
			$this->assign('cate',$cate);
			$this->assign('show',$show);
			return $this->fetch();
		}
		
	}
}