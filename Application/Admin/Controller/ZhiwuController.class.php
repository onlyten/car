<?php
namespace Admin\Controller;
use Think\Controller;
class ZhiwuController extends CommonController {
    public function zhiwu_list(){

		$m = D('ZhiwuBumen');
        if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
        $zhiwu = $m->order('zhiwu_name_ch desc')->page($page_now.',10')->select();
		//dump($zhiwu);
         //echo $m->_sql();
        $zhiwu_count = $m->where($map)->count();
        if ($zhiwu_count % 10 == 0) {
            $zhiwu_pagenum = (int)($zhiwu_count/10);
        }else{
            $zhiwu_pagenum = (int)($zhiwu_count/10)+1;
        }
        $this->assign('zhiwu',$zhiwu);
        $this->assign('page_now',$page_now);
        $this->assign('zhiwu_count',$zhiwu_count);
        $this->assign('zhiwu_pagenum',$zhiwu_pagenum);
		$this->display();
		//dump($zhiwu);
    }
    public function zhiwu_add()
    {
		$m = M('bumen');
		$bumen = $m->select();
		$this->assign('bumen',$bumen);
        $this->display();
    }

    public function zhiwu_add_update()
    {
		$Zhiwu = D("Zhiwu"); // 实例化User对象
			if (!$Zhiwu->create()){
				// 如果创建失败 表示验证没有通过 输出错误提示信息
				exit($Zhiwu->getError());
			}else{
			 // 验证通过 可以进行其他数据操作
			 }
		$data['bumen_id'] = I('post.bumen_id');
        $data['zhiwu_name_ch'] = I('post.zhiwu_name_ch');
        $data['admin_or'] = I('post.admin_or');
        $data['remark'] = I('post.remark');
        $img_filename = gen_random();
        $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
        //$upload_path = './Public/upload/img/';
        //move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
        //if ($_FILES["picurl"]['size'] != 0) {
         //   $data['factory_img'] = $img_filename.$ext;
       // }
        // dump($data);
        if (I('post.zhiwu_name_ch')) {
            $m = M('zhiwu');
            if ($m->data($data)->add()) {
               $this->success('添加成功',U('zhiwu_list'));
            }else{
                $this->error('添加失败，请重试',U('zhiwu_list'));
            }
        }
		
        
    }

    public function zhiwu_delete_update()
    {
        $delete_id = I('post.delete_id');
        // dump($delete_id);
        $map['id']  = array('in',$delete_id);
        $m = M('zhiwu');
        if ($m->where($map)->delete()) {
            $this->success('删除成功',U('zhiwu_list'));
        }else{
            $this->error('删除失败，请重试',U('zhiwu_list'));
        }
    }

    public function zhiwu_modify()
    {
		$m = M('bumen');
		$bumen = $m->select();
		$this->assign('bumen',$bumen);

        $zhiwu_id = I('get.zhiwu_id');
        $m = M('zhiwu');
        $zhiwu = $m->where('id = '.$zhiwu_id)->limit(1)->select();
        $this->assign('zhiwu',$zhiwu);
        $this->display();
    }

    public function zhiwu_modify_update()
    {
        // dump(I('get.'));
        $zhiwu_id = I('get.zhiwu_id');
		$data['bumen_id'] = I('post.bumen_id');
        $data['zhiwu_name_ch'] = I('post.zhiwu_name_ch');
        $data['admin_or'] = I('post.admin_or');
        $data['remark'] = I('post.remark');
        //if ($_FILES["picurl"]['size'] != 0) {
            //$img_filename = gen_random();
            //$ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
            //$upload_path = './Public/upload/img/';
           // move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
           // $data['factory_img'] = $img_filename.$ext;
       // }
        $m = M('zhiwu');
        if ($m->where('id = '.$zhiwu_id)->save($data)) {
            $this->success('修改成功',U('zhiwu_list'));
        }else{
            $this->error('修改失败，请重试',U('zhiwu_list'));
        }
    }

	public function zhiwu_check() {
		header("Content-Type:text/html;charset=utf-8");
		$zhiwu = D("Zhiwu"); // 实例化User对象
		if (!$zhiwu->create()) {
			// 指定新增数据
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			$this->error($zhiwu->getError ());
		}

	}

}