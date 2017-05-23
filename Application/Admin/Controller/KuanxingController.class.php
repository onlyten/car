<?php
namespace Admin\Controller;
use Think\Controller;
class KuanxingController extends CommonController {
    public function kuanxing_list(){
        $m = M('kuanxing');
        if (I('get.search_condition')) {
            $search_condition = I('search_condition');
            $map['kuanxing_name_ch'] = array('like','%'.$search_condition.'%');
        }
        // dump($map);
        if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
        $kuanxing = $m->order('kuanxing_weight
		desc')->where($map)->page($page_now.',10')->select();
        // echo $m->_sql();
        $kuanxing_count = $m->where($map)->count();
        if ($kuanxing_count % 10 == 0) {
            $kuanxing_pagenum = (int)($kuanxing_count/10);
        }else{
            $kuanxing_pagenum = (int)($kuanxing_count/10)+1;
        }
        $this->assign('kuanxing',$kuanxing);
        $this->assign('page_now',$page_now);
        $this->assign('kuanxing_count',$kuanxing_count);
        $this->assign('kuanxing_pagenum',$kuanxing_pagenum);
        $this->assign('search_condition',$search_condition);
		$this->display();
    }

    public function kuanxing_add()
    {
        $this->display();
    }

    public function kuanxing_add_update()
    {
		$Kuanxing = D("Kuanxing"); // 实例化User对象
			if (!$Kuanxing->create()){
				// 如果创建失败 表示验证没有通过 输出错误提示信息
				exit($Kuanxing->getError());
			}else{
			 // 验证通过 可以进行其他数据操作
			 }
        $data['kuanxing_name_ch'] = I('post.kuanxing_name_ch');
        $data['kuanxing_display'] = I('post.kuanxing_display');
        $data['kuanxing_weight'] = I('post.kuanxing_weight');
        $img_filename = gen_random();
        $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
       $upload_path = './Public/upload/img/';
       move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
       if ($_FILES["picurl"]['size'] != 0) {
           $data['kuanxing_img'] = $img_filename.$ext;
       }
        // dump($data);
        if (I('post.kuanxing_name_ch')) {
            $m = M('kuanxing');
            if ($m->data($data)->add()) {
               $this->success('添加成功',U('kuanxing_list'));
            }else{
                $this->error('添加失败，请重试',U('kuanxing_list'));
            }
        }
        
    }

    public function kuanxing_delete_update()
    {
        $delete_id = I('post.delete_id');
        // dump($delete_id);
        $map['id']  = array('in',$delete_id);
        $m = M('kuanxing');
        if ($m->where($map)->delete()) {
            $this->success('删除成功',U('kuanxing_list'));
        }else{
            $this->error('删除失败，请重试',U('kuanxing_list'));
        }
    }

    public function kuanxing_modify()
    {
        $kuanxing_id = I('get.kuanxing_id');
        $m = M('kuanxing');
        $kuanxing = $m->where('id = '.$kuanxing_id)->limit(1)->select();
        //dump($kuanxing);
		//echo $m->_sql();
        $this->assign('kuanxing',$kuanxing);
        $this->display();
    }

    public function kuanxing_modify_update()
    {
        // dump(I('get.'));
        $kuanxing_id = I('get.kuanxing_id');
        $data['kuanxing_name_ch'] = I('post.kuanxing_name_ch');
        $data['kuanxing_display'] = I('post.kuanxing_display');
        $data['kuanxing_weight'] = I('post.kuanxing_weight');
       // if ($_FILES["picurl"]['size'] != 0) {
          //  $img_filename = gen_random();
          //  $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
          //  $upload_path = './Public/upload/img/';
          //  move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
          //  $data['kuanxing_img'] = $img_filename.$ext;
      //  }
        $m = M('kuanxing');
        if ($m->where('id = '.$kuanxing_id)->save($data)) {
            $this->success('修改成功',U('kuanxing_list'));
        }else{
            $this->error('修改失败，请重试',U('kuanxing_list'));
        }
    }

	public function kuanxing_check() {
		header("Content-Type:text/html;charset=utf-8");
		$kuanxing = D("Kuanxing"); // 实例化User对象
		if (!$kuanxing->create()) {
			// 指定新增数据
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			$this->error($kuanxing->getError ());
		}

	}

}