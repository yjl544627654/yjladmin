<?php
namespace app\admin\controller;
use \app\admin\admin;
use \think\Db; 
use \app\admin\model\AdverModel;

class Adver extends Admin
{

	public function index(){
		$db = new AdverModel;

		$list = $db->getAll();

		$page = $list->render();
		$this->assign('page',$page);
		$this->assign('list' , $list);
		return $this->fetch();
	}

	public function add(){
		$db = new AdverModel;

		if( request()->isPost() )
		{
			$data['name'] = $this->validata('name','请输入广告名');
			$data['url'] = input('post.url');
			$data['img'] = $this->validata('img','请上传图片');
			$data['sort'] = input('post.sort');
			$data['status'] = input('?post.status') == 'on' ? 1:0;
			$data['addtime'] = time();
			$data['type'] = $this->validata('type','请输入英文的标识名');

			$db->addAdver($data);
			$this->success('操作成功！','index');
		}
		else
		{
			
			//$show = $db->getOne();
			return $this->fetch();
		}
		
	}

	public function ajax_uploadimg(){
		$db = new AdverModel;

		$file = request()->file('files');
		$path = 'public/assets/img/adver/';
		$info = $file->validate(['size'=>112400,'ext'=>'jpg,png,gif,ico'])->move(ROOT_PATH . $path);
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
		$db = new AdverModel;
		$id = input('?get.id') ? input('get.id') : $this->error('参数错误！');
		$map['id'] = $id;
		if( request()->Post() ){

			$data['name'] = $this->validata('name','请输入广告名');
			$data['url'] = input('post.url');
			$data['img'] = $this->validata('img','请上传图片');
			$data['sort'] = input('post.sort');
			$data['type'] = $this->validata('type','请输入英文的标识名');
			$data['status'] = input('?post.status') == 'on' ? 1:0;

			$this->res($db->updateAdver($map,$data));
		}else{

			$show = $db->getOne($map);
			$this->assign('show',$show);
			return $this->fetch();
		}

	}

	public function del(){
		$db = new AdverModel;
		$id = input('?get.id') ? input('get.id') : $this->error('参数错误！');
		$map['id'] = $id;

		$this->res($db->delAdver($map));

	}

}