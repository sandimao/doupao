<?php
namespace app\index\controller;
use think\Db;

class Index	extends Base
{
    public function index()
    {
		$pro=Db::name('product')->where("cat_id",4)->select();
		$news=Db::name('article')->limit(4)->select();
		// $banner=Db::name('banner')->select();
		// $this->assign('banner',$banner);
		$this->assign('pro',$pro);
		$this->assign('news',$news);
        return $this->fetch();
    }
	public function about(){
		$page=Db::name('page')->where('page_name','公司简介')->find();
		$content=str_replace('&nbsp;','',$page['page_content']);
		$page['page_content']=$content;
         $this->assign('page',$page);
	return $this->fetch();
	}

	public function contact(){
		$page=Db::name('page')->where('page_name','联系我们')->find();
         $this->assign('page',$page);
	return $this->fetch();
	}
	public function history(){
		$page=Db::name('page')->where('page_name','发展历程')->find();
		$content=str_replace('&nbsp;','',$page['page_content']);
		$page['page_content']=$content;
		$this->assign('page',$page);
		return $this->fetch();
	}
	public function job(){
		$page=Db::name('page')->where('page_name','人才招聘')->find();
		$content=str_replace('&nbsp;','',$page['page_content']);
		$page['page_content']=$content;
		$this->assign('page',$page);
		return $this->fetch();
	}
	public function news(){
		$cid=$this->request->param();
		if(!empty($cid['cid'])){
			$cid=$cid['cid'];
		}else{
			$cid=0;
		}
		$catart=Db::name('cat_art')->select();
		$this->assign('catart',$catart);
		if($cid==0){
			$news=Db::name('article')->paginate(8);
	   }
		else{
		   $news=Db::name('article')->where("cat_id",$cid)->paginate(4);
		}
		$this->assign('news',$news);
		return $this->fetch();
	}
	public function news_detail(){
		$nid=$this->request->param();
		if(!empty($nid['nid'])){
			$nid=$nid['nid'];
		}
		$catart=Db::name('cat_art')->select();
		$this->assign('catart',$catart);
		$new=Db::name('article')->where("art_id",$nid)->find();
		$this->assign('new',$new);
		return $this->fetch();
	}


	public function product(){
		 $cid=$this->request->param();
		 if(!empty($cid['cid'])){
			 $cid=$cid['cid'];
		 }else{
			 $cid=0;
		 }
		 $catpro=Db::name('cat_pro')->select();
		 $this->assign('catpro',$catpro);
		if($cid==0){
			 $pro=Db::name('product')->paginate(8);
		}
		 else{
			$pro=Db::name('product')->where("cat_id",$cid)->paginate(4);
		 }
		$this->assign('pro',$pro);
		return $this->fetch();
	}




	public function product_detail(){
		$pid=$this->request->param();
		if(!empty($pid['pid'])){
			$pid=$pid['pid'];
		}
		$catpro=Db::name('cat_pro')->select();
		$this->assign('catpro',$catpro);
		$pro=Db::name('product')->where("pro_id",$pid)->find();
		$this->assign('pro',$pro);
		return $this->fetch();
	}



}
