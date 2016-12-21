<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//返回图片目录+文件
function img($img = null){
	if( !empty($img) ){
		return '/public/assets/img/portrait/'.$img;
	}else{
		return '/public/assets/img/portrait/logo_user.png';
	}
}

//生成随机字符
function randstr($length=6)
{   
  $hash='';
  $chars= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz@#!~?:-=';   
  $max=strlen($chars)-1;   
  mt_srand((double)microtime()*1000000);   
  for($i=0;$i<$length;$i++){   
  $hash.=$chars[mt_rand(0,$max)];   
  }   
  return $hash;   
}

function msg($msg){
  $html =  '<script src="/public/assets/js/yjladmin.js"></script>
            <script type="text/javascript"> msg("'.$msg.'") </script>';
  return; 
}