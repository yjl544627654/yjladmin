<?php
namespace app\admin\controller;
use \app\admin\admin;
use \think\Db; 
use \app\admin\model\SettingModel;
use \app\admin\model\NavModel;
use \app\admin\model\NavPageModel;
/**
* 
*/
class Setting extends Admin
{
	
	function site(){

		$set_db = new SettingModel;

		if( request()->isPost() ){

			$_POST['set_site'] =  $_POST['set_site']!='on' ? 0 : 1;
			$data = input('post.');
			$this->res($set_db->updateSetAll($data));

		}else{

			//$list = $set_db->getAll()->toArray();

			$arr = DB::name('setting')->select();

			foreach ($arr as $key => $v) {
				$show[$v['key']] = $v['value'];
			}
			$this->assign('site',$show);

			return $this->fetch();
		}
		
	}

	function ajax_uploadimg(){
		$set_db = new SettingModel;

		$filename = input('post.img_name');
		
		$file = request()->file($filename);
		$path = 'public/assets/img/'.$filename.'/';
		$info = $file->validate(['size'=>102400,'ext'=>'jpg,png,gif,ico'])->move(ROOT_PATH . $path);
		if($info){
			$img_path = '/'.$path.$info->getSaveName();
		}else{
			$msg = $file->getError();
		}
		if( !isset($msg) ){
			$map['key'] = $filename;
			$data['value'] = $img_path;
			$ret = $set_db->updateOne($map,$data);
			
			$res['status'] = 1;
			$res['img_path'] = $img_path;
			return $res;
		}else{
			$res['status'] = 0;
			$res['msg'] = $msg;
			return $res;
		}
		
	}


	public function nav(){

		$nva_db = new NavModel;

		$firstlist = $nva_db->getAllNav(['nav_id'=>0]);
		$lastlist = $nva_db->getAllNav(['nav_id'=>['>',0]]);
		
		$this->assign('lastlist',$lastlist);
		$this->assign('list',$firstlist);

		return $this->fetch();
	}

	public function nav_add(){

		$nva_db = new NavModel;

		if( request()->isPost() ){
			//dump($_POST);exit;

			$type = input('post.type') ;
			$nav_id = input('post.nav_id');

			if($type == 'link'){
				$data['route'] = $this->validata('link','请输入链接或者路由');
			}
			if( $nva_db->getVlaueNav(['id'=>$nav_id],'nav_id') > 0 ){
				$this->error('不好意思，目前只支持二级导航');
				exit;
			}
			$data['status'] = input('post.status') == 'on' ? 1 :0;
			$data['nav_id'] = $nav_id;
			$data['name'] = $this->validata('name','请输入导航名');
			$data['sort'] = input('sort');
			$data['type'] = $type;
			$data['ctime'] = time();

			$nav_id = $nva_db->addNav($data);
			if( $type == 'page' ){
				//单页面
				$page_id = DB::name('nav_page')->insertGetId([
						'nav_id' => $nav_id,
						'content' => input('post.editorValue'),
						'addtime' => time()
					]);
				$where['id'] =  $nav_id;
				$pagedate['page_id'] = $page_id ;
				$this->res( $nva_db->updateNav($where,$pagedate) );
			}

			$this->success('添加导航成功！','setting/nav');
		}else{

			$firstlist = $nva_db->getAllNav(['nav_id'=>0]);
			$lastlist = $nva_db->getAllNav(['nav_id'=>['>',0]]);
			
			$this->assign('lastlist',$lastlist);
			$this->assign('list',$firstlist);
			return $this->fetch();	

		}
	}

	public function nav_del(){
		$db = new NavModel;
		$page_db = new NavPageModel;

		$id = input('?get.id') ? input('get.id') :$error('参数错误！');

		$page_id = $db->getVlaueNav(['id'=>$id],'page_id');
		$where['id'] = $page_id;

		$page_db->delNavPage($where);

		$this->res( $db->delNav(['id'=>$id]));
	}

	public function nav_edit(){
		$nva_db = new NavModel;
		$id = input('?get.id') ? input('get.id') :$error('参数错误！');

		$where['id'] = $id;
		$show = $nva_db->getOneNav($where)->toArray();

		if( !empty($show['page_id']) ){
			$show['content'] = DB::name('nav_page')->where('nav_id',$id)->value('content');
			$ishasPage = 1;
		}

		if(request()->isPost()){

			$type = input('post.type') ;
			$nav_id = input('post.nav_id');

			if($type == 'link'){
				$data['route'] = $this->validata('link','请输入链接或者路由');

			}
			if( $nva_db->getVlaueNav(['id'=>$nav_id],'nav_id') > 0 ){
				$this->error('不好意思，目前只支持二级导航'); 
			}
			$data['status'] = input('post.status') == 'on' ? 1 :0;
			$data['nav_id'] = $nav_id;
			$data['name'] = $this->validata('name','请输入导航名');
			$data['sort'] = input('sort');
			$data['type'] = $type;
			$data['ctime'] = time();

			$nav_id = $nva_db->updataNav(['id'=>$id],$data);
			if( $type == 'page' ){
				//单页面
				if(  !isset($ishasPage) ){

					$map['id'] = DB::name('nav_page')->insertGetId([
						'nav_id' => $id,
						'content' => input('post.editorValue'),
						'addtime' => time()
					]);

					$pagedate['page_id'] = $map['id'] ;
					$this->res( $nva_db->updateNav($map,$pagedate) );

				}else{
					$map['id'] = DB::name('nav_page')->where(['nav_id'=>$id])->update([
						'content' => input('post.editorValue'),
					]);
				}
				
			}
			$this->success('修改导航成功！');

		}else{

			$nav1 = $nva_db->getAllNav(['nav_id'=>0]);
			$nav2 = $nva_db->getAllNav(['nav_id'=>['>',0]]);
			
			$this->assign('show',$show);
			$this->assign('nav1',$nav1);
			$this->assign('nav2',$nav2);
			//dump($show);exit;
			return $this->fetch();
		}
		
	}
}

