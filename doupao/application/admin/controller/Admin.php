<?php
namespace app\admin\controller;
use think\Db;
class Admin extends Base
{
    public function list()
    {
		
			$blog=Db::name('admin')->paginate(5);
	
		//当前页
		$current=$blog->currentPage();
		//每页数据数量
		$limit=$blog->listRows();
		$con=($current-1)*$limit;
		
		$this->assign('blog',$blog);
		$this->assign('a',$con+1);
        return $this->fetch();
    }
	public function add()
    {
		if($_POST){
			$data=[
				'admin_name'=>$this->request->post('admin_name'),
				'admin_pwd'=>md5($this->request->post('admin_pwd')),
			];
			$res=Db::name('admin')->insert($data);
			if($res){//添加成功
				$this->success('添加成功','/admin/admin/list');
			}else{
				$this->error('数据执行有误');
			}
		}
        return $this->fetch();
    }
	public function edit($cid){
		//查询当前详细数据
		$cate=Db::name('admin')->where("admin_id",$cid)->find();
		$this->assign('cate',$cate);
		if($_POST){
			$data=[
				'admin_name'=>$this->request->post('admin_name'),
				'admin_pwd'=>md5($this->request->post('admin_pwd')),
			];
			//隐藏域
			$c_id=$this->request->post('admin_id');
			$res=Db::name('admin')->where("admin_id",$c_id)->update($data);
			if($res){
				$this->success('编辑成功','/admin/admin/list');
			}else{
				$this->error('数据执行有误','/admin/admin/edit');
			}
		}
		return $this->fetch();
	}
	//删除
	public function del($cid){
		//echo $cid;die;
		//$cid就是ajax传递过来的需要删除的数据的主键
		//利用主键作为条件做删除功能
		$res=Db::name('admin')->delete($cid);
		//如果需要其他条件删除就要加where方法
		if($res){
			echo 1;die;
		}else{
			echo Db::name('admin')->getLastSql();die;
		}
	}
}
