<?php
namespace Admin\Controller;
use Think\Controller;
class StaffController extends CommonController {
    public function staff_list(){
        $m = D('StaffZhiwu');
		//dump(I('get.'));
        if (I('get.search_condition') != "空" && I('get.search_condition') != "" ) {
            $search_condition = I('search_condition');
            $map['bumen_id'] = array('eq',$search_condition);
        }

		if (I('get.search_condition1') != "空" && I('get.search_condition') != "" ) {
            $search_condition1 = I('search_condition1');
            $map['zhiwu_id'] = array('eq',$search_condition1);
        }
        if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
        $staff = $m->order('staff_name_ch desc')->where($map)->page($page_now.',10')->select();
		//dump($staff);
         //echo $m->_sql();
        $staff_count = $m->where($map)->count();
        if ($staff_count % 10 == 0) {
            $staff_pagenum = (int)($staff_count/10);
        }else{
            $staff_pagenum = (int)($staff_count/10)+1;
        }
		$m = M('bumen');
		$bumen = $m->select();
		$this->assign('bumen',$bumen);

		$m = M('zhiwu');
		$zhiwu = $m->select();
		$this->assign('zhiwu',$zhiwu);

        $this->assign('staff',$staff);
        $this->assign('page_now',$page_now);
        $this->assign('staff_count',$staff_count);
        $this->assign('staff_pagenum',$staff_pagenum);
        $this->assign('search_condition',$search_condition);
		$this->assign('search_condition1',$search_condition1);
		$this->display();
    }


    public function staff_add()
    {
		$m = M('bumen');
		$bumen = $m->select();
		$this->assign('bumen',$bumen);

		$m = M('zhiwu');
		$zhiwu = $m->select();
		$this->assign('zhiwu',$zhiwu);
		$this->display();
    }

    public function staff_add_update()
    {
		$Staff = D("Staff"); // 实例化User对象
			if (!$Staff->create()){
				// 如果创建失败 表示验证没有通过 输出错误提示信息
				exit($Staff->getError());
			}else{
			 // 验证通过 可以进行其他数据操作
			 }
        $data['staff_number'] = I('post.staff_number');
        $data['bumen_id'] = I('post.bumen_id');
        $data['zhiwu_id'] = I('post.zhiwu_id');
		$data['staff_name_ch'] = I('post.staff_name_ch');
		$data['pwds'] = I('post.pwds');
		$data['staff_name'] = I('post.staff_name');
		$data['creatime'] = date('Y-m-d',time());
		$data['staff_sex'] = I('post.staff_sex');
		$data['account_use'] = I('post.account_use');
        $data['email'] = I('post.email');
        $img_filename = gen_random();
        $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
       // $upload_path = './Public/upload/img/';
       // move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
       // if ($_FILES["picurl"]['size'] != 0) {
       //     $data['factory_img'] = $img_filename.$ext;
      //  }
        // dump($data);
        if (I('post.staff_name_ch')) {
            $m = M('staff');
            if ($m->data($data)->add()) {
               $this->success('添加成功',U('staff_list'));
            }else{
                $this->error('添加失败，请重试',U('staff_list'));
            }
        }
        
    }

    public function staff_delete_update()
    {
        $delete_id = I('post.delete_id');
        // dump($delete_id);
        $map['id']  = array('in',$delete_id);
        $m = M('staff');
        if ($m->where($map)->delete()) {
            $this->success('删除成功',U('staff_list'));
        }else{
            $this->error('删除失败，请重试',U('staff_list'));
        }
    }

    public function staff_modify()
    {
		$m = M('bumen');
		$bumen = $m->select();
		$this->assign('bumen',$bumen);

		$m = M('zhiwu');
		$zhiwu = $m->select();
		$this->assign('zhiwu',$zhiwu);

        $staff_id = I('staff_id');
        $m = M('staff');
        $staff = $m->where('id = '.$staff_id)->limit(1)->select();
        //dump($staff);
        $this->assign('staff',$staff);
        $this->display();
    }

    public function staff_modify_update()
    {
        //dump(I('get.'));
        $staff_id = I('get.staff_id');
        $data['staff_number'] = I('post.staff_number');
        $data['bumen_id'] = I('post.bumen_id');
        $data['zhiwu_id'] = I('zhiwu_id');
		$data['staff_name_ch'] = I('staff_name_ch');
		$data['pwds'] = I('pwds');
		$data['staff_name'] = I('staff_name');
		$data['staff_sex'] = I('staff_sex');
		$data['account_use'] = I('account_use');
        $data['email'] = I('post.email');
       // if ($_FILES["picurl"]['size'] != 0) {
         //   $img_filename = gen_random();
         //   $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
          //  $upload_path = './Public/upload/img/';
          //  move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
         //   $data['guige_img'] = $img_filename.$ext;
      //  }
        $m = M('staff');
        if ($m->where('id = '.$staff_id)->save($data)) {
            $this->success('修改成功',U('staff_list'));
        }else{
            $this->error('修改失败，请重试',U('staff_list'));
        }
		//dump($data);
    }

public function staff_check() {
		header("Content-Type:text/html;charset=utf-8");
		$staff = D("Staff"); // 实例化User对象
		if (!$staff->create()) {
			// 指定新增数据
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			$this->error($staff->getError ());
		}

	}

}