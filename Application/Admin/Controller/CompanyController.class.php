<?php
namespace Admin\Controller;
use Think\Controller;
class CompanyController extends CommonController {
    public function company_list(){
        $m = M('company');
        if (I('get.search_condition')) {
            $search_condition = I('search_condition');
            $map['company_name_ch'] = array('like','%'.$search_condition.'%');
        }
        // dump($map);
        if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
        $company = $m->order('company_weight desc')->where($map)->page($page_now.',10')->select();
        // echo $m->_sql();
        $company_count = $m->where($map)->count();
        if ($company_count % 10 == 0) {
            $company_pagenum = (int)($company_count/10);
        }else{
            $company_pagenum = (int)($company_count/10)+1;
        }
        $this->assign('company',$company);
        $this->assign('page_now',$page_now);
        $this->assign('company_count',$company_count);
        $this->assign('company_pagenum',$company_pagenum);
        $this->assign('search_condition',$search_condition);
		$this->display();
    }

    public function company_add()
    {
        $this->display();
    }

    public function company_add_update()
    {
		$Company = D("Company"); // 实例化User对象
			if (!$Company->create()){
				// 如果创建失败 表示验证没有通过 输出错误提示信息
				exit($Company->getError());
			}else{
			 // 验证通过 可以进行其他数据操作
			 }
        $data['company_name_ch'] = I('post.company_name_ch');
        $data['company_introduct'] = I('post.company_introduct');
        $data['company_person'] = I('post.company_person');
		$data['company_phone'] = I('post.company_phone');
		$data['company_address'] = I('post.company_address');
        $data['company_display'] = I('post.company_display');
        $data['company_weight'] = I('post.company_weight');
        $img_filename = gen_random();
        $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
        $upload_path = './Public/upload/img/';
        move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
        if ($_FILES["picurl"]['size'] != 0) {
            $data['company_img'] = $img_filename.$ext;
        }
        // dump($data);
        if (I('post.company_name_ch')) {
            $m = M('company');
            if ($m->data($data)->add()) {
               $this->success('添加成功',U('company_list'));
            }else{
                $this->error('添加失败，请重试',U('company_list'));
            }
        }
        
    }

    public function company_delete_update()
    {
        $delete_id = I('post.delete_id');
        // dump($delete_id);
        $map['id']  = array('in',$delete_id);
        $m = M('company');
        if ($m->where($map)->delete()) {
            $this->success('删除成功',U('company_list'));
        }else{
            $this->error('删除失败，请重试',U('company_list'));
        }
    }

    public function company_modify()
    {
        $company_id = I('get.company_id');
        $m = M('company');
        $company = $m->where('id = '.$company_id)->limit(1)->select();
        // dump($company);
        $this->assign('company',$company);
        $this->display();
    }

    public function company_modify_update()
    {
        // dump(I('get.'));
        $company_id = I('get.company_id');
        $data['company_name_ch'] = I('post.company_name_ch');
        $data['company_introduct'] = I('post.company_introduct');
        $data['company_person'] = I('post.company_person');
		$data['company_phone'] = I('post.company_phone');
		$data['company_address'] = I('post.company_address');
        $data['company_display'] = I('post.company_display');
        $data['company_weight'] = I('post.company_weight');
        if ($_FILES["picurl"]['size'] != 0) {
            $img_filename = gen_random();
            $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
            $upload_path = './Public/upload/img/';
            move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
            $data['company_img'] = $img_filename.$ext;
        }
        $m = M('company');
        if ($m->where('id = '.$company_id)->save($data)) {
            $this->success('修改成功',U('company_list'));
        }else{
            $this->error('修改失败，请重试',U('company_list'));
        }
    }

	public function company_check() {
		header("Content-Type:text/html;charset=utf-8");
		$company = D("Company"); // 实例化User对象
		if (!$company->create()) {
			// 指定新增数据
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			$this->error($company->getError ());
		}

	}

}