<?php
namespace app\index\controller;
use think\Db;

class Base extends \think\Controller
{
    public function initialize()
    {
	$nav=Db::name('nav')->where("parent_id",0)->select();
    $nav_list=Db::name('nav')->select();
        foreach($nav as $k=>$value){
            foreach($nav_list as $v){
                if($value['nav_id']==$v['parent_id']){
                    $nav[$k]['son'][]=$v;
                }
            }
        }
        $this->assign('nav',$nav);

    $banner=Db::name('banner')->select();
    $this->assign('banner',$banner);
        return $this->fetch();

  

    }
	
}
