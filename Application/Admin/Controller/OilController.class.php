<?php
namespace Admin\Controller;
use Think\Controller;
class OilController extends CommonController {
    public function oil_list(){
        $m = M('oil');
        if (I('get.search_condition')) {
            $search_condition = I('search_condition');
            $map['oil_name_ch'] = array('like','%'.$search_condition.'%');
        }
        // dump($map);
        if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
        $oil = $m->order('oil_name_ch desc')->where($map)->page($page_now.',10')->select();
        // echo $m->_sql();
        $oil_count = $m->where($map)->count();
        if ($oil_count % 10 == 0) {
            $oil_pagenum = (int)($oil_count/10);
        }else{
            $oil_pagenum = (int)($oil_count/10)+1;
        }
        $this->assign('oil',$oil);
        $this->assign('page_now',$page_now);
        $this->assign('oil_count',$oil_count);
        $this->assign('oil_pagenum',$oil_pagenum);
        $this->assign('search_condition',$search_condition);
		$this->display();
    }

    public function oil_add()
    {
        $this->display();
    }

    public function oil_add_update()
    {
		$Oil = D("Oil"); // 实例化User对象
			if (!$Oil->create()){
				// 如果创建失败 表示验证没有通过 输出错误提示信息
				exit($Oil->getError());
			}else{
			 // 验证通过 可以进行其他数据操作
			 }
        $data['oil_name_ch'] = I('post.oil_name_ch');
        $data['oil_display'] = I('post.oil_display');
        $data['oil_weight'] = I('post.oil_weight');
        $img_filename = gen_random();
        $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
       $upload_path = './Public/upload/img/';
       move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
       if ($_FILES["picurl"]['size'] != 0) {
           $data['oil_img'] = $img_filename.$ext;
       }
        // dump($data);
        if (I('post.oil_name_ch')) {
            $m = M('oil');
            if ($m->data($data)->add()) {
               $this->success('添加成功',U('oil_list'));
            }else{
                $this->error('添加失败，请重试',U('oil_list'));
            }
        }
        
    }

    public function oil_delete_update()
    {
        $delete_id = I('post.delete_id');
        // dump($delete_id);
        $map['id']  = array('in',$delete_id);
        $m = M('oil');
        if ($m->where($map)->delete()) {
            $this->success('删除成功',U('oil_list'));
        }else{
            $this->error('删除失败，请重试',U('oil_list'));
        }
    }

    public function oil_modify()
    {
        $oil_id = I('get.oil_id');
        $m = M('oil');
        $oil = $m->where('id = '.$oil_id)->limit(1)->select();
        // dump($oil);
        $this->assign('oil',$oil);
        $this->display();
    }

    public function oil_modify_update()
    {
        // dump(I('get.'));
        $oil_id = I('get.oil_id');
        $data['oil_name_ch'] = I('post.oil_name_ch');
        $data['oil_display'] = I('post.oil_display');
        $data['oil_weight'] = I('post.oil_weight');
        if ($_FILES["picurl"]['size'] != 0) {
            $img_filename = gen_random();
            $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
            $upload_path = './Public/upload/img/';
            move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
            $data['oil_img'] = $img_filename.$ext;
        }
        $m = M('oil');
        if ($m->where('id = '.$oil_id)->save($data)) {
            $this->success('修改成功',U('oil_list'));
        }else{
            $this->error('修改失败，请重试',U('oil_list'));
        }
    }
	public function oil_check() {
		header("Content-Type:text/html;charset=utf-8");
		$oil = D("Oil"); // 实例化User对象
		if (!$oil->create()) {
			// 指定新增数据
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			$this->error($oil->getError ());
		}
	}

}