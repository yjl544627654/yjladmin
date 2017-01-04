<?php
namespace app\home\controller;
use \think\Controller;  
use \think\Db; 
use \think\Request;
use \app\home\home;
use \app\admin\model\NewModel;
use \app\admin\model\AdverModel;

class Index extends Home
{
    public function index()
    {   
        //banner 图
        $db_adv = new AdverModel();
        $db_new = new NewModel();
        $db_adv = new AdverModel();

        $topbanner = $db_adv->getBanner(['type'=>'portal_banner']);
        //设置banner默认图
        $show_delafut = $db_adv->where('type','portal_banner')->order('sort',"DESC")->find();

        $banner = $db_adv->where('type','index_banner')->select();

        //文章
        $new = $db_new->field('id,title,sort,auth,cate_id,addtime')->select();

        //第三方服务市场图片
        $fuwu = $db_adv->where('type','fuwu')->select();

        //网站建设介绍
        $db_product = new \app\admin\model\ProductModel();
        $web_show = $db_product->where('id','10')->find();
        $web_list = $db_product->where('p_id',$web_show['id'])->select();

        //产业基地
        $base['changye'] = $db_product->where('p_id','21')->select();
        $base['yuanqu'] =  $db_product->where('p_id','22')->select();

        $this->assign('base',$base);
        $this->assign('web_list',$web_list);
        $this->assign('web_show',$web_show);
        $this->assign('fuwu',$fuwu);
        $this->assign('new',$new);
        $this->assign('banner',$banner);
        $this->assign('topbanner',$topbanner);
        $this->assign('show_delafut',$show_delafut);
        return $this->fetch();
    }

    public function portal(){
        
        $db_new = new NewModel();
        //资讯排行
        $orderlist = NewModel::order('click desc,  addtime desc , sort desc  ')->limit(11)->select();
        $n = 0;
        foreach ($orderlist as $k => $v) {
             $n = $n+1;
            $orderlist[$k]['num'] = $n;

        }


        //网络支付
        $intpaylist = $db_new->getIndexListNew(['cate_id'=>'3']);

        //新闻报道
        $newlist = $db_new->getIndexListNew(['cate_id'=>'4']);

        //跨境电商
        $kualist = $db_new->getIndexListNew(['cate_id'=>'8']);

        //电商实战
        $shizhanlist = $db_new->getIndexListNew(['cate_id'=>'9']);

        //农村电商
        $nonglist = $db_new->getIndexListNew(['cate_id'=>'7']);

        //政务信息
        $zhenwulist = $db_new->getIndexListNew(['cate_id'=>'6']);

        //banner 图
        $db_adv = new AdverModel();
        
        $topbanner = $db_adv->getBanner(['type'=>'portal_banner']);
        //设置banner默认图
        $show_delafut = $db_adv->where('type','portal_banner')->order('sort',"DESC")->find();

        //中间横条
        $dianzi_heng = $db_adv->where('type','dianzi_heng')->order('sort',"DESC")->find();

        //图文资讯
        $tuwen = $db_adv->getBanner(['type'=>'tuwen']);
        //dump($tuwen);exit;
        $this->assign('tuwen',$tuwen);
        $this->assign('dianzi_heng',$dianzi_heng);
        $this->assign('show_delafut',$show_delafut);
        $this->assign('topbanner',$topbanner);
        $this->assign('intpaylist',$intpaylist);
        $this->assign('orderlist',$orderlist);
        $this->assign('newlist',$newlist);
        $this->assign('kualist',$kualist);
        $this->assign('shizhanlist',$shizhanlist);
        $this->assign('nonglist',$nonglist);
        $this->assign('zhenwulist',$zhenwulist);
        return $this->fetch();
        
    }

    public function portal_show(){

        $id = input('get.id') ? input("get.id") : $this->error('参数有误');

        $db_new = new NewModel();

        //改变查看数量
        $db_new->where('id',$id)->setInc('click');
        //文章内容
        $show = $db_new->where('id',$id)->find();
        //上下一编
        $order_id = $db_new->where('cate_id',$show['cate_id'])
                            ->order('sort','DESC')
                            ->column('id');
        foreach ($order_id as $k => $v) {
            if($v == $id){
                $up_id = $k-1;
                $low_id = $k+1;
            }
        }
        $uplowdata['up'] = $db_new->where('id',$order_id[$up_id])->find();
        $uplowdata['low'] = $db_new->where('id',$order_id[$low_id])->find();

        //分类
        $cate_db = new \app\admin\model\CategoryModel;
        $catelist = $cate_db->getCate();

        //面包屑
        $loaction = $cate_db->where('id',$show['cate_id'])->find();
        
        $this->assign('loaction',$loaction);
        $this->assign('catelist',$catelist);
        $this->assign('uplowdata',$uplowdata);
        $this->assign('show',$show);
        return $this->fetch();
    }

    public function catelist(){

        $id = input('get.id') ? input("get.id") : $this->error('参数有误');
        $db_new = new NewModel();

        $list = $db_new->where(['cate_id'=>$id])->paginate(20,false,['query' => ['id'=>$id]]);
        
         //分类
        $cate_db = new \app\admin\model\CategoryModel;
        $catelist = $cate_db->getCate();

        //当前分类信息
        $show = $cate_db->where('id',$id)->find();
        
        $this->assign('show',$show);
        $this->assign('lists',$list);
        $this->assign('catelist',$catelist);

        return $this->fetch();
    }

    public function base(){

        $db_product = new \app\admin\model\ProductModel();
       
        //产业基地
        $base['changye'] = $db_product->where('p_id','21')->select();
        $base['yuanqu'] =  $db_product->where('p_id','22')->select();

        $this->assign('base',$base);

        return $this->fetch();

    }

    public function base_show(){
        $id = input('get.id') ? input('get.id') : $this->error('参数错误');

        $db_product = new \app\admin\model\ProductModel();

        $base =  $db_product->where('id', $id)->find();

        $this->assign('base',$base);

        return $this->fetch();

    }

    public function job(){
        
        //招聘文章
        $db_new = new NewModel();
        $list = $db_new->where('cate_id','12')->select();
        $this->assign('joblist',$list);
        return $this->fetch();
    }

    public function msg(){
        $db_msg = new \app\admin\model\MsgModel();
        if( request()->isPost() ){
            
            if(!captcha_check(input('post.sys_check'))){
                $this->error('验证码不对');
            };
            $data['username'] = $this->validata('username','请输入联系人姓名');
            $data['tel'] = $this->validata('tel','请输入电话或者手机号码');
            $data['email'] = $this->validata('email','请输入邮箱');
            $data['content'] = $this->validata('content','请输入要留言的内容');
            $data['addtime'] = time();

            $res = $db_msg->insertGetId($data);
            if( $res )$this->success('留言成功！');
                else   $this->error('留言失败！');

        }else{
             return $this->fetch();
        }
       
    }

    public function alonepage() {

        $id = input('get.id') ? input('get.id') : $this->error('参数错误');

        $db_nav = new \app\admin\model\NavModel();
        $show = $db_nav->where('id',$id)->find();
        $this->assign('show',$show);
        return $this->fetch();
    }


    
}
