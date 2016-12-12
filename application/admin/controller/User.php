<?php
namespace app\admin\controller;
use \app\admin\admin;
use \think\Db; 
use \app\admin\model\UserModel;
use \app\admin\model\UserGroupModel;


class User extends Admin
{
	
	function index(){
		
		$user_db = new UserModel;

		$list = $user_db->getList();
		
		$page = $list->render();
		
		$this->assign('page',$page);
		$this->assign('list',$list);
		return $this->fetch();
	}

	function add(){

		if( request()->isPost() ){
			$user_db = new UserModel;

			$file = request()->file('img');
			if( isset($file)){
				// 移动到框架应用根目录public/upload/portrait目录下
				$info = $file->move(ROOT_PATH . 'public/assets/img/portrait');
				if( $info ){
					$data['img'] = $info->getSaveName();
				}
			}
			$user = input('post.username');
			
			if (empty($user)) {
				$this->error('请输入用户名');
			}else{
				$user_db->CheckUserName($user) ? $this->error('用户名已被使用'):'';
			}
			$pwd = input('post.pwd') ? input('post.pwd') :$this->error('请输入密码');
			$data['hash'] = randstr();
			$data['pwd'] = md5($pwd.$data['hash']);
			$data['username'] = $user; 
			$data['emali'] = input('post.emali') ;
			$data['mark'] = input('post.mark');
			$data['truename'] = input('post.truename');
			$data['status'] = input('post.status')=='on'? 1 :0;

			$this->res($user_db->addUser($data));exit;

			
		}else{

			$group_db = new UserGroupModel;

			$group = $group_db->getGroup();

			$this->assign('group',$group);
			return $this->fetch();
		}
	}

	function edit(){

		$id = input('?get.id') ? input('get.id') : $this->error('参数错误');
		$user_db = new UserModel;

		if( request()->isPost() ){ 
			//提交post  input('files')
			$file = request()->file('img');
			if( isset($file)){
				// 移动到框架应用根目录public/upload/portrait目录下
				$info = $file->move(ROOT_PATH . 'public/assets/img/portrait');
				if( $info ){
					$data['img'] = $info->getSaveName();
				}
			}
			$user_hash = $user_db->getUserHash(input('get.id'));

			$data['pwd'] = md5(input('post.pwd').$user_hash);
			$data['uid'] = input('post.uid') ;
			$data['truename'] = input('post.truename');
			$data['emali'] = input('post.emali') ;
			$data['mark'] = input('post.mark');
			$data['status'] = input('post.status')=='on'? 1 :0;

			$map['id'] = input('get.id');

			$ret = $user_db->editUser($data,$map);
			if ($ret) {
				$this->success('操作成功');
			}else{
				$this->error('未作更改');
			}

			
		}else{

			$group_db = new UserGroupModel;

			$group = $group_db->getGroup();

			$show = $user_db->getOneUser($id);

			$this->assign('group',$group);
			$this->assign('user',$show);

			return $this->fetch();
		}
		
	}

	function del(){
		$id = input('get.id') ? input('get.id') : $this->error('参数错误');
		$user_db = new UserModel();
		$this->res($user_db->delUser($id));
	}
}