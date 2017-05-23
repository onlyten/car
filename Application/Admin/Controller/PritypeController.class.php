<?php
namespace Admin\Controller;
use Think\Controller;
class pritypeController extends Controller {
    public function pritype_list(){
        $m = M('pritype');
        // dump($map);
        if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
        $pritype = $m->order('pritype_weight desc')->where($map)->page($page_now.',10')->select();
        // echo $m->_sql();
        $pritype_count = $m->where($map)->count();
        if ($pritype_count % 10 == 0) {
            $pritype_pagenum = (int)($pritype_count/10);
        }else{
            $pritype_pagenum = (int)($pritype_count/10)+1;
        }
        $this->assign('pritype',$pritype);
        $this->assign('page_now',$page_now);
        $this->assign('pritype_count',$pritype_count);
        $this->assign('pritype_pagenum',$pritype_pagenum);
		$this->display();
    }

    public function pritype_add()
    {
        $this->display();
    }

    public function pritype_add_update()
    {  
		$Pritype = D("Pritype"); // 实例化User对象
			if (!$Pritype->create()){
				// 如果创建失败 表示验证没有通过 输出错误提示信息
				exit($Pritype->getError());
			}else{
			 // 验证通过 可以进行其他数据操作
			 }
        $data['pritype_name'] = I('post.pritype_name');
        $data['pritype_weight'] = I('post.pritype_weight');
        $img_filename = gen_random();
        $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
       $upload_path = './Public/upload/img/';
       move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
       if ($_FILES["picurl"]['size'] != 0) {
           $data['pritype_img'] = $img_filename.$ext;
       }
        // dump($data);
        if (I('post.pritype_name')) {
            $m = M('pritype');
            if ($m->data($data)->add()) {
               $this->success('添加成功',U('pritype_list'));
            }else{
                $this->error('添加失败，请重试',U('pritype_list'));
            }
        }
        
    }

    public function pritype_delete_update()
    {
        $delete_id = I('post.delete_id');
        // dump($delete_id);
        $map['id']  = array('in',$delete_id);
        $m = M('pritype');
        if ($m->where($map)->delete()) {
            $this->success('删除成功',U('pritype_list'));
        }else{
            $this->error('删除失败，请重试',U('pritype_list'));
        }
    }

    public function pritype_modify()
    {
        $pritype_id = I('get.pritype_id');
        $m = M('pritype');
        $pritype = $m->where('id = '.$pritype_id)->limit(1)->select();
        // dump($pritype);
        $this->assign('pritype',$pritype);
        $this->display();
    }

    public function pritype_modify_update()
    {
        // dump(I('get.'));
        $pritype_id = I('get.pritype_id');
        $data['pritype_name'] = I('post.pritype_name');
        $data['pritype_weight'] = I('post.pritype_weight');
        if ($_FILES["picurl"]['size'] != 0) {
            $img_filename = gen_random();
            $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
            $upload_path = './Public/upload/img/';
            move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
            $data['pritype_img'] = $img_filename.$ext;
        }
        $m = M('pritype');
        if ($m->where('id = '.$pritype_id)->save($data)) {
            $this->success('修改成功',U('pritype_list'));
        }else{
            $this->error('修改失败，请重试',U('pritype_list'));
        }
    }

	public function pritype_check() {
		header("Content-Type:text/html;charset=utf-8");
		$pritype = D("Pritype"); // 实例化User对象
		if (!$pritype->create()) {
			// 指定新增数据
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			$this->error($pritype->getError ());
		}

	}

}