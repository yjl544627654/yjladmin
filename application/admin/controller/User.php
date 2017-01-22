<?php
namespace app\admin\controller;
use \app\admin\admin;
use \think\Db; 
use \app\admin\model\UserModel;
use \app\admin\model\UserGroupModel;


class User extends Admin
{
	public $defalutPower = 'index/index,login/notpower,login/out,login/cacheClear,login/login,';
	
	function index(){

		if( request()->isGet() ){
			$where['username'] = ['like','%'.input('get.name').'%'];
		}
		$user_db = new UserModel;

		$list = $user_db->getList($where);
		
		$page = $list->render();

		$this->assign('page',$page);
		$this->assign('list',$list);
		return $this->fetch();
	}

	function add(){

		if( request()->isPost() ){
			$user_db = new UserModel;
			$user = input('post.username');
			$arr = input('post.') ;

			if (empty($user)) {
				$this->errorflash('新建失败！请输入用户名');
			}elseif( $user_db->CheckUserName($user) ){
				$this->errorflash('用户名已被使用');
			}else{
				$pwd = input('post.pwd') ? input('post.pwd') :$this->errorflash('请输入密码');

				if( !$this->getflash('error') ){  // 如果没有error的消息才提交操作
					$data['img'] = input('post.img');
					$data['hash'] = randstr();
					$data['pwd'] = md5($pwd.$data['hash']);
					$data['username'] = $user; 
					//$data['uid'] = input('post.uid') ;
					$data['emali'] = input('post.emali');
					$data['mark'] = input('post.mark');
					$data['truename'] = input('post.truename');
					$data['status'] = input('post.status')=='on'? 1 :0;
					$data['addtime'] = time();
					
					$data['power'] = $this->defalutPower;

					$ret = $user_db->addUser($data);
					$this->setflash('添加'.$user.'账号成功');
				}
			}
			
		}

		$group_db = new UserGroupModel;

		$group = $group_db->getGroup();

		$this->assign('arr',$arr);
		$this->assign('group',$group);
		return $this->fetch();
	}

	function edit(){

		$id = input('?get.id') ? input('get.id') : $this->error('参数错误');
		$user_db = new UserModel;

		if( $user_db->checkAdmin($id) ){
			$this->error('没有权限操作admin管理员','User/index');
		}

		if( request()->isPost() ){ 
			//提交post  input('files')
			$user_hash = $user_db->getUserHash(input('get.id'));

			$data['pwd'] = md5(input('post.pwd').$user_hash);
			//$data['uid'] = input('post.uid') ;
			$data['truename'] = input('post.truename');
			$data['emali'] = input('post.emali') ;
			$data['mark'] = input('post.mark');
			$data['status'] = input('post.status')=='on'? 1 :0;
			$data['updatetime'] = time();
			$data['img'] = input('post.img');

			$map['id'] = input('get.id');

			$ret = $user_db->editUser($data,$map);
			if ($ret) {
				$this->setflash('修改成功');
			}else{
				$this->setflash('未作更改');
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

	public function del(){
		$id = input('get.id') ? input('get.id') : $this->error('参数错误');
		$user_db = new UserModel();
		$is_admin = $user_db->where('id',$id)->value('username');
		if( $is_admin == 'admin' ){
			$this->errorflash('没有权限操作admin管理员');
		}
		$user_db->delUser($id);
		$this->setflash('删除成功！','user/index');
	}

	public function ajax_uploadimg(){
		$db = new UserModel;

		$file = request()->file('files');
		$path = 'public/assets/img/portrait/';
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

	function set_power(){
		$id = input('get.id') ? input('get.id') : $this->error('参数错误');

		$db = new UserModel;

		if( request()->isPost()){
			$data['power'] = implode(',', $_POST['powers'] );
			$data['power'] = $this->defalutPower.$data['power'] ;
			$ret = $db->where('id',$id)->update($data);
			$ret ? $this->setflash('修改权限成功！'): $this->setflash('没有修改权限');
		}

		$power = $db->where('id',$id)->value('power');
		$arr_power = explode(',', $power);
		$this->assign('powers',$arr_power);
		return $this->fetch('power');
	}
	

}