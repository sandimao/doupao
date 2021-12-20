<?php
namespace app\admin\controller;
use think\Db;
class Banner extends Base
{
    public function list()
    {
		//列表查询+分页
			$blog=Db::name('banner')->paginate(2);
	
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
		//博客分类添加
		if($_POST){
			$data=[
				'banner_title'=>$this->request->post('banner_title'),
				'banner_url'=>$this->request->post('banner_url'),
				'banner_ord'=>$this->request->post('banner_ord'),
				'banner_addtime'=>time(),
			];
			if($_FILES['banner_path']['size']){
				$file = request()->file('banner_path');//path是name值
				//foreach($files as $file){
				$info = $file->move('uploads');//上传到根目录uploads内
					//需要先新建uploads
					if($info){ //上传成功
						$data['banner_path']=$info->getSaveName();
						// $old_arr=Db::name('product')->field('pro_img')->where("pro_id",$c_id)->find();
						// 
						// 
					}else{ //上传失败
						$this->error($file->getError());
					}
				}
			$res=Db::name('banner')->insert($data);
			if($res){//添加成功
				$this->success('添加成功','/admin/banner/list');
			}else{
				$this->error('数据执行有误');
			}
		}
        return $this->fetch();
    }
	public function edit($cid){
		//查询当前详细数据
		$cate=Db::name('banner')->where("banner_id",$cid)->find();
		$this->assign('cate',$cate);
		if($_POST){
			$data=[
				'banner_title'=>$this->request->post('banner_title'),
				'banner_ord'=>$this->request->post('banner_ord'),
				'banner_url'=>$this->request->post('banner_url'),
				'banner_addtime'=>strtotime($this->request->post('banner_addtime')),
			];
			//隐藏域
			$c_id=$this->request->post('banner_id');
			if($_FILES['banner_path']['size']){
				$old_arr=Db::name('banner')->field('banner_path')->where("banner_id",$c_id)->find();
				$old_photo=$old_arr["banner_path"];
				if(!empty($old_photo)){
						 unlink("uploads/$old_photo");
					 }
				$file = request()->file('banner_path');//path是name值
				//foreach($files as $file){
				$info = $file->move('uploads');//上传到根目录uploads内
					//需要先新建uploads
					if($info){ //上传成功
						$data['banner_path']=$info->getSaveName();
						// $old_arr=Db::name('product')->field('pro_img')->where("pro_id",$c_id)->find();
						// 
						// 
	
					}else{ //上传失败
						$this->error($file->getError());
					}
				}
			$res=Db::name('banner')->where("banner_id",$c_id)->update($data);
			if($res){
				$this->success('编辑成功','/admin/banner/list');
			}else{
				$this->error('数据执行有误','/admin/banner/edit');
			}
		}
		return $this->fetch();
	}
	//删除
	public function del($cid){
		//echo $cid;die;
		//$cid就是ajax传递过来的需要删除的数据的主键
		//利用主键作为条件做删除功能
		$res=Db::name('banner')->delete($cid);
		//如果需要其他条件删除就要加where方法
		if($res){
			echo 1;die;
		}else{
			echo Db::name('banner')->getLastSql();die;
		}
	}
}
