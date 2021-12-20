<?php
namespace app\admin\controller;
use think\Db;
class Nav extends Base
{
    public function list($pid)
    {
		$pid=$this->request->param();
		if(!empty($pid['pid'])){
			$pid=$pid['pid'];
		}else{
			$pid=0;
		}
		$this->assign('pid',$pid);
		//列表查询+分页
			$blog=Db::name('nav')->where("parent_id",$pid)->paginate(5);
	
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
		$pid=$this->request->param();
		if(!empty($pid['pid'])){
			$pid=$pid['pid'];
		}else{
			$pid=0;
		}
		$nav=Db::name('nav')->where("nav_id",$pid)->find();
		$this->assign('nav',$nav);
		//博客分类添加
		if($_POST){
			    $pid=$this->request->post('nav_id');
			$data=[
				'nav_name'=>$this->request->post('nav_name'),
				'nav_url'=>$this->request->post('nav_url'),
				'nav_ord'=>$this->request->post('nav_ord'),
				'parent_id'=>$pid,
			];
			$res=Db::name('nav')->insert($data);
			if($res){//添加成功
				$this->success('添加成功','/admin/nav/list/pid/'.$pid);
			}else{
				$this->error('数据执行有误');
			}
		}
        return $this->fetch();
    }
	public function edit($cid){
		//查询当前详细数据
		$cate=Db::name('nav')->where("nav_id",$cid)->find();
		$this->assign('cate',$cate);
		if($_POST){
			$data=[
				'nav_name'=>$this->request->post('nav_name'),
				'nav_ord'=>$this->request->post('nav_ord'),
				'nav_url'=>$this->request->post('nav_url'),
			];
			//隐藏域
			$c_id=$this->request->post('nav_id');
			$res=Db::name('nav')->where("nav_id",$c_id)->update($data);
			if($res){
				$this->success('编辑成功','/admin/nav/list');
			}else{
				$this->error('数据执行有误','/admin/nav/edit');
			}
		}
		return $this->fetch();
	}
	//删除
	public function del($cid){
		//echo $cid;die;
		//$cid就是ajax传递过来的需要删除的数据的主键
		//利用主键作为条件做删除功能
		$res=Db::name('nav')->delete($cid);
		//如果需要其他条件删除就要加where方法
		if($res){
			echo 1;die;
		}else{
			echo Db::name('nav')->getLastSql();die;
		}
	}
}
