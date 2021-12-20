<?php
namespace app\admin\controller;
use think\Db;
class News extends Base
{
    public function list()
    {
		//列表查询+分页
			$blog=Db::name('article')->alias('p')->join('cat_art c','c.cat_id=p.cat_id')->paginate(3);
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
		$catart=Db::name('cat_art')->select();
		$this->assign('catart',$catart);
		//博客分类添加
		if($_POST){
			$data=[
				'art_title'=>$this->request->post('art_title'),
				'art_click'=>$this->request->post('art_click'),
				'art_content'=>$this->request->post('art_content'),
				'art_addtime'=>time(),
				'art_ord'=>$this->request->post('art_ord'),
				'is_show'=>$this->request->post('is_show'),
				'cat_id'=>$this->request->post('cat_id'),
			];
			if($_FILES['art_img']['size']){
			$file = request()->file('art_img');//path是name值
			//foreach($files as $file){
				
							$info = $file->move('uploads');//上传到根目录uploads内
				//需要先新建uploads
				if($info){ //上传成功
					//$info->getExtension();上传类型
					//$info->getSaveName();上传图片的包含时间文件的路径
					//$info->getFilename(); 上传之后的图片名称
					$data['art_img']=$info->getSaveName();
				}else{ //上传失败
					$this->error($file->getError());
				}
			}
			$res=Db::name('article')->insert($data);
			if($res){//添加成功
				$this->success('添加成功','/admin/news/list');
			}else{
				$this->error('数据执行有误','/admin/news/add');
			}
		}
        return $this->fetch();
    }
	public function edit($cid){
		//查询当前详细数据
		$catart=Db::name('cat_art')->select();
		$this->assign('catart',$catart);
		$cate=Db::name('article')->where("art_id",$cid)->find();
		$this->assign('cate',$cate);
		if($_POST){
			$data=[
				'art_title'=>$this->request->post('art_title'),
				'art_click'=>$this->request->post('art_click'),
				'art_content'=>$this->request->post('art_content'),
				'art_addtime'=>strtotime($this->request->post('art_addtime')),
				'art_ord'=>$this->request->post('art_ord'),
				'is_show'=>$this->request->post('is_show'),
				'cat_id'=>$this->request->post('cat_id'),
			];
			//隐藏域
			$c_id=$this->request->post('art_id');

			if($_FILES['art_img']['size']){
			$old_arr=Db::name('article')->field('art_img')->where("art_id",$c_id)->find();
			$old_photo=$old_arr["art_img"];
			if(!empty($old_photo)){
				 	unlink("uploads/$old_photo");
				 }
			$file = request()->file('art_img');//path是name值
			//foreach($files as $file){
			$info = $file->move('uploads');//上传到根目录uploads内
				//需要先新建uploads
				if($info){ //上传成功
					$data['art_img']=$info->getSaveName();
					// $old_arr=Db::name('product')->field('pro_img')->where("pro_id",$c_id)->find();
					// 
					// 

				}else{ //上传失败
					$this->error($file->getError());
				}
			}
			//}
			$res=Db::name('article')->where("art_id",$c_id)->update($data);
			if($res){
				$this->success('编辑成功','/admin/news/list');
			}else{
				$this->error('数据执行有误','/admin/news/edit');
			}
		}
		return $this->fetch();
	}
	//删除
	public function del($cid){
		//echo $cid;die;
		//$cid就是ajax传递过来的需要删除的数据的主键
		//利用主键作为条件做删除功能
		$old_arr=Db::name('article')->field('art_img')->where("art_id",$cid)->find();
		$old_photo=$old_arr["art_img"];
		if(!empty($old_photo)){
				 unlink("uploads/$old_photo");
			 }
		$res=Db::name('article')->delete($cid);

		//如果需要其他条件删除就要加where方法
		if($res){
			echo 1;die;
		}else{
			echo Db::name('cat_art')->getLastSql();die;
		}
	}
}
