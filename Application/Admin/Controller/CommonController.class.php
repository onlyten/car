<?php
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller {

	public function _initialize ()
	{
		if (is_null(session('uid'))) {
			$this->redirect('Login/login');
		}
		$url_now = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		// 输出用户名
		$username = session('username');
		$this->assign('username',$username);
		$uid = session('uid');
		if ($uid == '0') {
			// 超级管理员
			return true;
		}else{
			// 普通用户，非超级管理员
			$m = D('QuanxianJiedian');
			$jiedian = $m->where('staff.id = '.$uid)->select();
			foreach ($jiedian as $key => $value) {
				$jiedian[$key]['jiedian_url'] = str_replace("/","\/",$value['jiedian_url']);
				$jiedian[$key]['jiedian_url'] = "/^".$jiedian[$key]['jiedian_url']."/";
				/*dump($url_now);
				dump($jiedian[$key]['jiedian_url']);*/
				if (preg_match($jiedian[$key]['jiedian_url'],$url_now)) {
					// echo "1";
					return true;
				}
			}
			$this->error('对不起，您没有足够的权限');
			// dump($jiedian);
		}
		// dump(session('uid'));
		

	}

}