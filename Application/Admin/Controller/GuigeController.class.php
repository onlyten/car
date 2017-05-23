<?php
namespace Admin\Controller;
use Think\Controller;
class GuigeController extends Controller {
    public function guige_list(){
		$d = D('GuigeFactory');
		$guige_factory = $d->select();
        $m = D('GuigeFactory');
		//dump(I('get.'));
		if(I('get.chexing_id')){
		   $chexing_id = I('chexing_id');
		   $map['chexing_id'] = array('eq',$chexing_id);
		}
        if (I('post.search_condition') != "空" && I('post.search_condition') != "" ) {
            $search_condition = I('search_condition');
            $map['factory_id'] = array('eq',$search_condition);
        }

		if (I('post.search_condition1') != "空" && I('post.search_condition') != "" ) {
            $search_condition1 = I('search_condition1');
            $map['chexing_id'] = array('eq',$search_condition1);
        }

		if (I('post.search_condition2') != "空" && I('post.search_condition') != "" ) {
            $search_condition2 = I('search_condition2');
            $map['year_id'] = array('eq',$search_condition2);
        }

		//echo "<br> $search_condition <br>";
        //dump($map);
        if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
		//dump($map);
        $guige = $m->order('guige_weight desc')->where($map)->page($page_now.',10')->select();
         //echo $m->_sql();
        $guige_count = $m->where($map)->count();

		//var_dump($guige);

		//echo "<br> geshu $guige_count <br>";


        if ($guige_count % 10 == 0) {
            $guige_pagenum = (int)($guige_count/10);
        }else{
            $guige_pagenum = (int)($guige_count/10)+1;
        }
		$m = M('factory');

		//echo "<br> abcd $m <br>";


		$factory = $m->select();

		//echo $m->_sql();



		$this->assign('factory',$factory);



		  //$guige_count =  $m->count();

		//echo "<br> geshu $guige_count <br>";

		$m = M('chexing');
		$chexing = $m->select();
		$this->assign('chexing',$chexing);

		$m = M('year');
		$year = $m->select();
		$this->assign('year',$year);

        $this->assign('guige',$guige);
        $this->assign('page_now',$page_now);
        $this->assign('guige_count',$guige_count);
        $this->assign('guige_pagenum',$guige_pagenum);
        $this->assign('search_condition',$search_condition);
		$this->assign('search_condition1',$search_condition1);
		$this->assign('search_condition2',$search_condition2);
		$this->display();
    }


    public function guige_add()
    {
		$m = M('factory');
		$factory = $m->select();
		$this->assign('factory',$factory);

		$m = M('chexing');
		$chexing = $m->select();
		$this->assign('chexing',$chexing);

		$m = M('year');
		$year = $m->select();
		$this->assign('year',$year);

		$m = M('guige');
		$guige = $m->select();
		$this->assign('guige',$guige);

		$m = M('kuanxing');
		$kuanxing = $m->select();
		$this->assign('kuanxing',$kuanxing);

		$m = M('oil');
		$oil = $m->select();
		$this->assign('oil',$oil);
		$this->display();
    }

    public function guige_add_update()
    {
		$Guige = D("Guige"); // 实例化User对象
			if (!$Guige->create()){
				// 如果创建失败 表示验证没有通过 输出错误提示信息
				exit($Guige->getError());
			}else{
			 // 验证通过 可以进行其他数据操作
			 }
        $data['factory_id'] = I('post.factory_id');
        $data['chexing_id'] = I('post.chexing_id');
        $data['year_id'] = I('post.year_id');
		$data['chexing_type'] = I('post.chexing_type');
		$data['oil_id'] = I('post.oil_id');
		$data['kuanxing_id'] = I('post.kuanxing_id');
		$data['guige_name_ch'] = I('post.guige_name_ch');
		$data['liter'] = I('post.liter');
        $data['guige_display'] = I('post.guige_display');
        $data['guige_weight'] = I('post.guige_weight');
        $img_filename = gen_random();
        $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
       // $upload_path = './Public/upload/img/';
       // move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
       // if ($_FILES["picurl"]['size'] != 0) {
       //     $data['factory_img'] = $img_filename.$ext;
      //  }
        // dump($data);
        if (I('post.guige_name_ch')) {
            $m = M('guige');
            if ($m->data($data)->add()) {
               $this->success('添加成功',U('guige_list'));
            }else{
                $this->error('添加失败，请重试',U('guige_list'));
            }
        }
        
    }

    public function guige_delete_update()
    {
        $delete_id = I('post.delete_id');
        // dump($delete_id);
        $map['id']  = array('in',$delete_id);
        $m = M('guige');
        if ($m->where($map)->delete()) {
            $this->success('删除成功',U('guige_list'));
        }else{
            $this->error('删除失败，请重试',U('guige_list'));
        }
    }

    public function guige_modify()
    {
		$m = M('factory');
		$factory = $m->select();
		$this->assign('factory',$factory);

		$m = M('chexing');
		$chexing = $m->select();
		$this->assign('chexing',$chexing);

		$m = M('year');
		$year = $m->select();
		$this->assign('year',$year);

		$m = M('guige');
		$guige = $m->select();
		$this->assign('guige',$guige);

		$m = M('kuanxing');
		$kuanxing = $m->select();
		$this->assign('kuanxing',$kuanxing);

		$m = M('oil');
		$oil = $m->select();
		$this->assign('oil',$oil);

        $guige_id = I('get.guige_id');
        $m = M('guige');
        $guige = $m->where('id = '.$guige_id)->limit(1)->select();
        //dump($guige);
        $this->assign('guige',$guige);
        $this->display();
    }

    public function guige_modify_update()
    {
        //dump(I('get.'));
        $guige_id = I('get.guige_id');
        $data['factory_id'] = I('post.factory_id');
        $data['chexing_id'] = I('post.chexing_id');
        $data['year_id'] = I('year_id');
		$data['chexing_type'] = I('chexing_type');
		$data['oil_id'] = I('oil_id');
		$data['kuanxing_id'] = I('kuanxing_id');
		$data['guige_name_ch'] = I('guige_name_ch');
		$data['liter'] = I('liter');
        $data['guige_display'] = I('post.guige_display');
        $data['guige_weight'] = I('post.guige_weight');
       // if ($_FILES["picurl"]['size'] != 0) {
         //   $img_filename = gen_random();
         //   $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
          //  $upload_path = './Public/upload/img/';
          //  move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
         //   $data['guige_img'] = $img_filename.$ext;
      //  }
        $m = M('guige');
        if ($m->where('id = '.$guige_id)->save($data)) {
            $this->success('修改成功',U('guige_list'));
        }else{
            $this->error('修改失败，请重试',U('guige_list'));
        }
    }


	public function guige_check() {
		header("Content-Type:text/html;charset=utf-8");
		$guige = D("Guige"); // 实例化User对象
		if (!$guige->create()) {
			// 指定新增数据
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			$this->error($guige->getError ());
		}

	}
	public function chexing_select()
    {
        $factory_id = I('post.factory_id');
        $m = M('chexing');
        $chexing = $m->where("factory_id = '".$factory_id."'")->select();
        $this->ajaxReturn($chexing);
    }

}