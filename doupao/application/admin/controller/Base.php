<?php
namespace app\admin\controller;
use think\Db;
use think\facade\Session;
use think\facade\Cookie;
class Base	extends \think\Controller
{
    public function initialize()
    {
        $session=Session::has('is_login');
        if($session){
            $is_login=Session::get('is_login');
             if($is_login==1){
                 $is_cookie=Cookie::get('admin_name');
                 if($is_cookie){
                     $admin_name=Cookie::get('admin_name');
                      Cookie::set('admin_name',$admin_name,3600);
                 }else{
                     Session::delete('is_login');
                     $this->redirect('/admin/login/login');
                 }
              
             }
            
        } else{
            $this->redirect('/admin/login/login');
             }
    }

}