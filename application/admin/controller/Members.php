<?php
namespace app\admin\controller;
use \app\admin\admin;
use \app\admin\model\MemberModel;


class Members extends Admin
{
	
	public function index(){

		$db = new MemberModel;
		$name = input('get.name');
		if( isset($name) ){
			$map['username'] = ['like',"%$name%"];
		}
		$list = $db->where($map)->paginate(10);
		$page = $list->render();

		$this->assign('list',$list);
		$this->assign('page',$page);
		return $this->fetch();
	}


	public function add(){
		$db = new MemberModel;

		if( request()->isPost() ){

			$user = input('post.username');
			
			if (empty($user)) {
				$this->error('请输入用户名');
			}else{
				$db->CheckMembersName($user) ? $this->error('用户名已被使用'):'';
			}
			$pwd = input('post.pwd') ? input('post.pwd') :$this->error('请输入密码');
			$data['hash'] = randstr();
			$data['pwd'] = md5($pwd.$data['hash']);
			$data['username'] = $user; 
			$data['emali'] = input('post.emali') ;
			$data['mark'] = input('post.mark');
			$data['truename'] = input('post.truename');
			$data['status'] = input('post.status')=='on'? 1 :0;
			$data['addtime'] = time();
			$data['img'] = input('post.img');

			$ret = $db->insert($data);

			if ($ret) {
				$this->success('添加用户成功','index');
			}else{
				$this->error('添加失败');
			}


		}else{
			return $this->fetch();
		}
		
	}
	

	public function edit(){
		$db = new MemberModel;

		$id = input('get.id') ? input('get.id') : $this->error('参数错误');

		if( request()->isPost() ){ 
			//提交post  input('files')
			$user_hash = $db->getMembersHash(input('get.id'));

			$data['pwd'] = md5(input('post.pwd').$user_hash);
			$data['truename'] = input('post.truename');
			$data['emali'] = input('post.emali') ;
			$data['mark'] = input('post.mark');
			$data['status'] = input('post.status')=='on'? 1 :0;
			$data['updatetime'] = time();
			$data['img'] = input('post.img');

			$map['id'] = input('get.id');

			$ret = $db->where($map)->update($data);
			if ($ret) {
				$this->success('操作成功','index');
			}else{
				$this->error('未作更改');
			}

			
		}else{

			$show = $db->where('id',$id)->find();

			$this->assign('user',$show);
			return $this->fetch();
		}

	}

	public function del(){
		$db = new MemberModel;

		$arr_id = $_POST['id'];
		if( is_array($arr_id) ){
			$this->res(MemberModel::destroy($arr_id));
		}else{
			$id = input('?get.id') ? input('get.id') : $this->error('参数错误');
			$this->res($db->where('id',$id)->delete());
		}
		
	}

	public function ajax_uploadimg(){
		$db = new MemberModel;

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
	
}