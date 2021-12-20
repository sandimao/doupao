<?php
namespace app\admin\controller;
use think\Db;
class Product extends Base
{
    public function list()
    {
		//列表查询+分页
			$blog=Db::name('product')->alias('p')->join('cat_pro c','c.cat_id=p.cat_id')->paginate(3);
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
		$catpro=Db::name('cat_pro')->select();
		$this->assign('catpro',$catpro);
		//博客分类添加
		if($_POST){
			$data=[
				'pro_name'=>$this->request->post('pro_name'),
				'pro_price'=>$this->request->post('pro_price'),
				'pro_text'=>$this->request->post('pro_text'),
				'pro_addtime'=>time(),
				'pro_ord'=>$this->request->post('pro_ord'),
				'is_show'=>$this->request->post('is_show'),
				'cat_id'=>$this->request->post('cat_id'),
			];
			if($_FILES['pro_img']['size']){
			$file = request()->file('pro_img');//path是name值
			//foreach($files as $file){
				
							$info = $file->move('uploads');//上传到根目录uploads内
				//需要先新建uploads
				if($info){ //上传成功
					//$info->getExtension();上传类型
					//$info->getSaveName();上传图片的包含时间文件的路径
					//$info->getFilename(); 上传之后的图片名称
					$data['pro_img']=$info->getSaveName();
				}else{ //上传失败
					$this->error($file->getError());
				}
			}
			$res=Db::name('product')->insert($data);
			if($res){//添加成功
				$this->success('添加成功','/admin/product/list');
			}else{
				$this->error('数据执行有误');
			}
		}
        return $this->fetch();
    }
	public function edit($cid){
		//查询当前详细数据
		$catpro=Db::name('cat_pro')->select();
		$this->assign('catpro',$catpro);
		$cate=Db::name('product')->where("pro_id",$cid)->find();
		$this->assign('cate',$cate);
		if($_POST){
			$data=[
				'pro_name'=>$this->request->post('pro_name'),
				'pro_price'=>$this->request->post('pro_price'),
				'pro_text'=>$this->request->post('pro_text'),
				'pro_addtime'=>strtotime($this->request->post('pro_addtime')),
				'pro_ord'=>$this->request->post('pro_ord'),
				'is_show'=>$this->request->post('is_show'),
				'cat_id'=>$this->request->post('cat_id'),
			];
			//隐藏域
			$c_id=$this->request->post('pro_id');

			if($_FILES['pro_img']['size']){
			$old_arr=Db::name('product')->field('pro_img')->where("pro_id",$c_id)->find();
			$old_photo=$old_arr["pro_img"];
			if(!empty($old_photo)){
				 	unlink("uploads/$old_photo");
				 }
			$file = request()->file('pro_img');//path是name值
			//foreach($files as $file){
			$info = $file->move('uploads');//上传到根目录uploads内
				//需要先新建uploads
				if($info){ //上传成功
					$data['pro_img']=$info->getSaveName();
					// $old_arr=Db::name('product')->field('pro_img')->where("pro_id",$c_id)->find();
					// 
					// 

				}else{ //上传失败
					$this->error($file->getError());
				}
			}
			//}
			$res=Db::name('product')->where("pro_id",$c_id)->update($data);
			if($res){
				$this->success('编辑成功','/admin/product/list');
			}else{
				$this->error('数据执行有误','/admin/product/edit');
			}
		}
		return $this->fetch();
	}
	//删除
	public function del($cid){
		//echo $cid;die;
		//$cid就是ajax传递过来的需要删除的数据的主键
		//利用主键作为条件做删除功能
		$old_arr=Db::name('product')->field('pro_img')->where("pro_id",$cid)->find();
		$old_photo=$old_arr["pro_img"];
		if(!empty($old_photo)){
				 unlink("uploads/$old_photo");
			 }
		$res=Db::name('product')->delete($cid);
		//如果需要其他条件删除就要加where方法
		if($res){
			echo 1;die;
		}else{
			echo Db::name('cat_art')->getLastSql();die;
		}
	}
}
