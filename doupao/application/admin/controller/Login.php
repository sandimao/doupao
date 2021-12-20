<?php
namespace app\admin\controller;
use think\Db;
use think\captcha\Captcha;
use think\facade\Session;
use think\facade\Cookie;
class Login	extends \think\Controller
{
    public function login()
    {
        if($_POST){
            $code=$this->request->post('code');
            if(!captcha_check($code)){
                $this->error('验证码输入有误');
            }else{
                $admin_name=$this->request->post('admin_name');
                $admin_password=md5($this->request->post('admin_password'));
                $admin=Db::name('admin')->where("admin_name='$admin_name' and admin_pwd='$admin_password'")->find();
                if($admin){
                    Session::set('admin_name',$admin_name);
                    Session::set('is_login','1');
                    Cookie::set('admin_name',$admin_name,3600);
                    $this->success('登录成功','/admin/index/index');
                }else{
                    $this->error('用户名和密码错误');
                }
            }
        }
        return $this->fetch();
    }
    public function verify(){
        $config =    [
            // 验证码字体大小
            'fontSize'    =>    30,    
            // 验证码位数
            'length'      =>    4,   
            // 关闭验证码杂点
            'useNoise'    =>    false, 
             'useCurve'   =>false,
             'useImgBg'   =>true,
        ];
        $captcha = new Captcha($config);
        return $captcha->entry();
    }

}