<?php
namespace Admin\Controller;
use Think\Controller;

class FactoryController extends CommonController {
	public function factory_list() {
		//厂商列表
		$m = M('factory');
		if (I('get.search_condition')) {
			$search_condition = I('search_condition');
			$map['factory_name_ch'] = array('like', '%' . $search_condition . '%'); //厂商名模糊查找
		}
		// dump($map);
		if (!I('get.page_now')) {
			$page_now = 1;
		} else {
			$page_now = I('get.page_now');
		}
		$factory = $m->order('factory_weight desc')->where($map)->page($page_now . ',10')->select();
		// echo $m->_sql();
		$factory_count = $m->where($map)->count();
		if ($factory_count % 10 == 0) {
			$factory_pagenum = (int) ($factory_count / 10);
		} else {
			$factory_pagenum = (int) ($factory_count / 10) + 1;
		}
		$this->assign('factory', $factory);
		$this->assign('page_now', $page_now);
		$this->assign('factory_count', $factory_count);
		$this->assign('factory_pagenum', $factory_pagenum);
		$this->assign('search_condition', $search_condition);
		$this->display();
	}

	public function factory_add() {
		//添加厂商
		$this->display();
	}

	public function factory_add_update() {
		//添加厂商后进行更新
		$Factory = D("Factory"); // 实例化User对象
		if (!$Factory->create()) {
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			exit($Factory->getError());
		} else {
			// 验证通过 可以进行其他数据操作
		}
		$data['factory_name_ch'] = I('post.factory_name_ch');
		$data['factory_name_en'] = I('post.factory_name_en');
		$data['factory_name_py'] = I('post.factory_name_py');
		$data['factory_display'] = I('post.factory_display');
		$data['rexiao'] = I('post.rexiao');
		$data['factory_weight'] = I('post.factory_weight');
		$data['zimu'] = getFirstCharter(I('post.factory_name_ch'));
		$img_filename = gen_random();
		$ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'], '.'), strlen($_FILES['picurl']['name']) - 1); //拿到后缀
		$upload_path = './Public/upload/img/';
		move_uploaded_file($_FILES['picurl']['tmp_name'], $upload_path . $img_filename . $ext);
		if ($_FILES["picurl"]['size'] != 0) {
			$data['factory_img'] = $img_filename . $ext;
		}
		// dump($data);
		if (I('post.factory_name_ch')) {
			$m = M('factory');
			if ($m->data($data)->add()) {
				$this->success('添加成功', U('factory_list'));
			} else {
				$this->error('添加失败，请重试', U('factory_list'));
			}
		}

	}

	public function factory_delete_update() {
		//删除厂商后更新
		$delete_id = I('post.delete_id');
		//dump($delete_id);
		$m = M('chexing');
		$chexing = $m->select();
		$long = count($chexing);
		$found = 0;
		for($i=0;$i<$long;$i++){
			if(in_array($chexing[$i]["factory_id"],$delete_id)){
				//echo "你好";
				$found = 1;
				$this->error('该厂商下有对应的车型,不能删除!', U('factory_list'));
			}
		}
		if($found == 0){
			$map['id'] = array('in', $delete_id);
			$m = M('factory');
			if ($m->where($map)->delete()) {
				$this->success('删除成功', U('factory_list'));
			} else {
				$this->error('删除失败，请重试', U('factory_list'));
			}
		}
		
	}

	public function factory_modify() {
		//对厂商进行修改
		$factory_id = I('get.factory_id');
		$m = M('factory');
		$factory = $m->where('id = ' . $factory_id)->limit(1)->select();
		// dump($factory);
		$this->assign('factory', $factory);
		$this->display();
	}

	public function factory_modify_update() {
		//对厂商修改之后进行更新
		// dump(I('get.'));
		$factory_id = I('get.factory_id');
		$data['factory_name_ch'] = I('post.factory_name_ch');
		$data['factory_name_en'] = I('post.factory_name_en');
		$data['factory_name_py'] = I('post.factory_name_py');
		$data['factory_display'] = I('post.factory_display');
		$data['rexiao'] = I('post.rexiao');
		$data['factory_weight'] = I('post.factory_weight');
		$data['zimu'] = getFirstCharter(I('post.factory_name_ch'));
		if ($_FILES["picurl"]['size'] != 0) {
			$img_filename = gen_random();
			$ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'], '.'), strlen($_FILES['picurl']['name']) - 1); //拿到后缀
			$upload_path = './Public/upload/img/';
			move_uploaded_file($_FILES['picurl']['tmp_name'], $upload_path . $img_filename . $ext);
			$data['factory_img'] = $img_filename . $ext;
		}
		$m = M('factory');
		if ($m->where('id = ' . $factory_id)->save($data)) {
			$this->success('修改成功', U('factory_list'));
		} else {
			$this->error('修改失败，请重试', U('factory_list'));
		}
	}

	public function factory_check() {
		header("Content-Type:text/html;charset=utf-8");
		$factory = D("Factory"); // 实例化User对象
		if (!$factory->create()) {
			// 指定新增数据
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			$this->error($factory->getError ());
		}

	}

}