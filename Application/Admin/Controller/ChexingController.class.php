<?php
namespace Admin\Controller;
use Think\Controller;
class ChexingController extends CommonController {
    public function chexing_list(){
$d = D('ChexingFactory');
$chexing_factory = $d->select();
//dump($chexing_factory);

		$m = D('ChexingFactory');
       // $m = M('chexing');

		if(I('get.factory_id')){
		   $factory_id = I('factory_id');
		   $map['factory_id'] = array('eq',$factory_id);
		}


        if (I('get.search_condition')) {
            $search_condition = I('search_condition');
            $map['chexing_name_ch'] = array('like','%'.$search_condition.'%');
        }
        // dump($map);
        if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
        $chexing = $m->order('chexing_weight desc')->where($map)->page($page_now.',10')->select();
         //echo $m->_sql();
        $chexing_count = $m->where($map)->count();
        if ($chexing_count % 10 == 0) {
            $chexing_pagenum = (int)($chexing_count/10);
        }else{
            $chexing_pagenum = (int)($chexing_count/10)+1;
        }
        $this->assign('chexing',$chexing);
        $this->assign('page_now',$page_now);
        $this->assign('chexing_count',$chexing_count);
        $this->assign('chexing_pagenum',$chexing_pagenum);
        $this->assign('search_condition',$search_condition);
		$this->display();
		//dump($chexing);
    }
    public function chexing_add()
    {
		$m = M('factory');
		$factory = $m->select();
		$this->assign('factory',$factory);
        $this->display();
    }

    public function chexing_add_update()
    {
		$Chexing = D("Chexing"); // 实例化User对象
			if (!$Chexing->create()){
				// 如果创建失败 表示验证没有通过 输出错误提示信息
				exit($Chexing->getError());
			}else{
			 // 验证通过 可以进行其他数据操作
			 }
		$data['factory_id'] = I('post.factory_id');
        $data['chexing_name_ch'] = I('post.chexing_name_ch');
        $data['chexing_name_en'] = I('post.chexing_name_en');
        $data['chexing_name_py'] = I('post.chexing_name_py');
        $data['guide_price'] = I('post.guide_price');
        $data['chexing_display'] = I('post.chexing_display');
        $data['chexing_weight'] = I('post.chexing_weight');
        $img_filename = gen_random();
        $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
        $upload_path = './Public/upload/img/';
        move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
        if ($_FILES["picurl"]['size'] != 0) {
           $data['chexing_img'] = $img_filename.$ext;
        }
        // dump($data);
        if (I('post.chexing_name_ch')) {
            $m = M('chexing');
            if ($m->data($data)->add()) {
               $this->success('添加成功',U('chexing_list'));
            }else{
                $this->error('添加失败，请重试',U('chexing_list'));
            }
        }
		
        
    }

    public function chexing_delete_update()
    {
        $delete_id = I('post.delete_id');
        // dump($delete_id);

		$m = M('guige');
		$guige = $m->select();
		$long = count($guige);
		$found = 0;
		for($i=0;$i<$long;$i++){
			if(in_array($guige[$i]["chexing_id"],$delete_id)){
				//echo "你好";
				$found = 1;
				$this->error('该车型下有对应的规格,不能删除!', U('chexing_list'));
			}
		}
		
		if($found == 0){
			$map['id']  = array('in',$delete_id);
			$m = M('chexing');
			if ($m->where($map)->delete()) {
				$this->success('删除成功',U('chexing_list'));
			}else{
				$this->error('删除失败，请重试',U('chexing_list'));
			}
		}
    }

    public function chexing_modify()
    {
		$m = M('factory');
		$factory = $m->select();
		$this->assign('factory',$factory);

        $chexing_id = I('get.chexing_id');
        $m = M('chexing');
        $chexing = $m->where('id = '.$chexing_id)->limit(1)->select();
        //dump($chexing);
        $this->assign('chexing',$chexing);
        $this->display();
    }


	public function chexing_check() {
		header("Content-Type:text/html;charset=utf-8");
		$chexing = D("Chexing"); // 实例化User对象
		if (!$chexing->create()) {
			// 指定新增数据
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			$this->error($chexing->getError ());
		}

	}

    public function chexing_modify_update()
    {
        // dump(I('get.'));
        $chexing_id = I('get.chexing_id');
		$data['factory_id'] = I('post.factory_id');
        $data['chexing_name_ch'] = I('post.chexing_name_ch');
        $data['chexing_name_en'] = I('post.chexing_name_en');
        $data['chexing_name_py'] = I('post.chexing_name_py');
        $data['guide_price'] = I('post.guide_price');
        $data['chexing_display'] = I('post.chexing_display');
        $data['chexing_weight'] = I('post.chexing_weight');
        if ($_FILES["picurl"]['size'] != 0) {
            $img_filename = gen_random();
            $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
            $upload_path = './Public/upload/img/';
            move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
            $data['chexing_img'] = $img_filename.$ext;
        }
        $m = M('chexing');
        if ($m->where('id = '.$chexing_id)->save($data)) {
            $this->success('修改成功',U('chexing_list'));
        }else{
            $this->error('修改失败，请重试',U('chexing_list'));
        }
    }

}