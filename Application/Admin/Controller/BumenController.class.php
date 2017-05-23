<?php
namespace Admin\Controller;
use Think\Controller;
class BumenController extends CommonController {
    public function bumen_list(){
		//部门列表
        $m = M('bumen');
        if (I('get.search_condition')) {
            $search_condition = I('search_condition');
            $map['bumen_name_ch'] = array('like','%'.$search_condition.'%');//部门名模糊查找
        }
        // dump($map);
        if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
        $bumen = $m->order('bumen_weight desc')->where($map)->page($page_now.',10')->select();
        // echo $m->_sql();
        $bumen_count = $m->where($map)->count();
        if ($bumen_count % 10 == 0) {
            $bumen_pagenum = (int)($bumen_count/10);
        }else{
            $bumen_pagenum = (int)($bumen_count/10)+1;
        }
        $this->assign('bumen',$bumen);
        $this->assign('page_now',$page_now);
        $this->assign('bumen_count',$bumen_count);
        $this->assign('bumen_pagenum',$bumen_pagenum);
        $this->assign('search_condition',$search_condition);
		$this->display();
    }

    public function bumen_add()
    {  //添加部门
        $this->display();
    }

    public function bumen_add_update()
    {  //添加部门后进行更新
		$Bumen = D("Bumen"); // 实例化User对象
			if (!$Bumen->create()){
				// 如果创建失败 表示验证没有通过 输出错误提示信息
				exit($Bumen->getError());
			}else{
			 // 验证通过 可以进行其他数据操作
			 }
        $data['bumen_name_ch'] = I('post.bumen_name_ch');
        $data['bumen_display'] = I('post.bumen_display');
        $data['bumen_weight'] = I('post.bumen_weight');
        $img_filename = gen_random();
        $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
        // dump($data);
        if (I('post.bumen_name_ch')) {
            $m = M('bumen');
            if ($m->data($data)->add()) {
               $this->success('添加成功',U('bumen_list'));
            }else{
                $this->error('添加失败，请重试',U('bumen_list'));
            }
        }
        
    }

    public function bumen_delete_update()
    {  //删除部门后更新
        $delete_id = I('post.delete_id');
        // dump($delete_id);
        $map['id']  = array('in',$delete_id);
        $m = M('bumen');
        if ($m->where($map)->delete()) {
            $this->success('删除成功',U('bumen_list'));
        }else{
            $this->error('删除失败，请重试',U('bumen_list'));
        }
    }

    public function bumen_modify()
    {  //对部门进行修改
        $bumen_id = I('get.bumen_id');
        $m = M('bumen');
        $bumen = $m->where('id = '.$bumen_id)->limit(1)->select();
        // dump($bumen);
        $this->assign('bumen',$bumen);
        $this->display();
    }

    public function bumen_modify_update()
    {  //对部门修改之后进行更新
        // dump(I('get.'));
        $bumen_id = I('get.bumen_id');
        $data['bumen_name_ch'] = I('post.bumen_name_ch');
        $data['bumen_display'] = I('post.bumen_display');
        $data['bumen_weight'] = I('post.bumen_weight');
        if ($_FILES["picurl"]['size'] != 0) {
            $img_filename = gen_random();
            $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
            $upload_path = './Public/upload/img/';
            move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
            $data['bumen_img'] = $img_filename.$ext;
        }
        $m = M('bumen');
        if ($m->where('id = '.$bumen_id)->save($data)) {
            $this->success('修改成功',U('bumen_list'));
        }else{
            $this->error('修改失败，请重试',U('bumen_list'));
        }
    }

	public function bumen_check() {
		header("Content-Type:text/html;charset=utf-8");
		$bumen = D("Bumen"); // 实例化User对象
		if (!$bumen->create()) {
			// 指定新增数据
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			$this->error($bumen->getError ());
		}
	}

}