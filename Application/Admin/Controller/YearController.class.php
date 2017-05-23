<?php
namespace Admin\Controller;
use Think\Controller;
class YearController extends CommonController {
    public function year_list(){
        $m = M('year');
        if (I('get.search_condition')) {
            $search_condition = I('search_condition');
            $map['year_name_ch'] = array('like','%'.$search_condition.'%');
        }
        // dump($map);
        if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
        $year = $m->order('year_name_ch desc')->where($map)->page($page_now.',10')->select();
        // echo $m->_sql();
        $year_count = $m->where($map)->count();
        if ($year_count % 10 == 0) {
            $year_pagenum = (int)($year_count/10);
        }else{
            $year_pagenum = (int)($year_count/10)+1;
        }
        $this->assign('year',$year);
        $this->assign('page_now',$page_now);
        $this->assign('year_count',$year_count);
        $this->assign('year_pagenum',$year_pagenum);
        $this->assign('search_condition',$search_condition);
		$this->display();
    }

    public function year_add()
    {
        $this->display();
    }

    public function year_add_update()
    {
		$Year = D("Year"); // 实例化User对象
			if (!$Year->create()){
				// 如果创建失败 表示验证没有通过 输出错误提示信息
				exit($Year->getError());
			}else{
			 // 验证通过 可以进行其他数据操作
			 }
        $data['year_name_ch'] = I('post.year_name_ch');
        $data['year_display'] = I('post.year_display');
        $data['year_weight'] = I('post.year_weight');
        $img_filename = gen_random();
        $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
       $upload_path = './Public/upload/img/';
       move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
       if ($_FILES["picurl"]['size'] != 0) {
           $data['year_img'] = $img_filename.$ext;
       }
        // dump($data);
        if (I('post.year_name_ch')) {
            $m = M('year');
            if ($m->data($data)->add()) {
               $this->success('添加成功',U('year_list'));
            }else{
                $this->error('添加失败，请重试',U('year_list'));
            }
        }
        
    }

    public function year_delete_update()
    {
        $delete_id = I('post.delete_id');
        // dump($delete_id);
        $map['id']  = array('in',$delete_id);
        $m = M('year');
        if ($m->where($map)->delete()) {
            $this->success('删除成功',U('year_list'));
        }else{
            $this->error('删除失败，请重试',U('year_list'));
        }
    }

    public function year_modify()
    {
        $year_id = I('get.year_id');
        $m = M('year');
        $year = $m->where('id = '.$year_id)->limit(1)->select();
        // dump($year);
        $this->assign('year',$year);
        $this->display();
    }

    public function year_modify_update()
    {
        // dump(I('get.'));
        $year_id = I('get.year_id');
        $data['year_name_ch'] = I('post.year_name_ch');
        $data['year_display'] = I('post.year_display');
        $data['year_weight'] = I('post.year_weight');
        if ($_FILES["picurl"]['size'] != 0) {
            $img_filename = gen_random();
            $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
            $upload_path = './Public/upload/img/';
            move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
            $data['year_img'] = $img_filename.$ext;
        }
        $m = M('year');
        if ($m->where('id = '.$year_id)->save($data)) {
            $this->success('修改成功',U('year_list'));
        }else{
            $this->error('修改失败，请重试',U('year_list'));
        }
    }



	public function year_check() {
		header("Content-Type:text/html;charset=utf-8");
		$year = D("Year"); // 实例化User对象
		if (!$year->create()) {
			// 指定新增数据
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			$this->error($year->getError ());
		}
	}

}