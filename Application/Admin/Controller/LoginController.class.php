<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {

	public function login ()
	{
		$this->display();

	}

	public function login_check()
	{
		$username = I('post.username');
		$password = I('post.password');
		$pingtai = I('post.user_type');
		// 超级管理员
		
			if ($username == C('USER_NAME') && $password == C('PASS_WORD')) {
				 session('uid','0');	
				 session('username',$username);
				// if($pingtai == 0){
				// 	$this->redirect('Factory/factory_list');
				// }
				// if($pingtai == 1){
				// 	echo  "<script>alert('即将进入平台2！');window.location.href='http://101.201.148.160/3car/index.php/Admin/Factory/factory_list'</script>";
				// }
				//echo "aaaaaaaaa<br/>";
				$fond = 1;
			}else{
				$m = M('staff');
				$staff = $m->select();
				foreach ($staff as $key => $value) {
					if ($username == $value['staff_name_ch'] && $password == $value['pwds']) {
						 session('uid',$value['id']);
						 session('username',$username);
						//$this->redirect('Factory/factory_list');
						$fond = 1;
						//echo "bbbbbbbbb<br/>";
					}
				}
				//$this->error('用户名或密码错误，请重试',U('login'));
			}

			$link = mysql_connect('182.92.11.136','root','tywxptdb3') or die('连接失败！');
			mysql_select_db('cardeal',$link)or die('数据库链接失败!');
			$sql = "select id from car_staff where staff_name_ch='".$username."' and pwds = '".$password."'";
			$res = mysql_query($sql);
			$b = mysql_num_rows($res);
			if($b){
				$fondd = 2;
				//echo "cccccccc<br/>";
			}
			
			mysql_close($link);

			if($fond == 1){
				if($fondd == 2){
					header("Location: http://101.201.148.160/car/guo.html"); 
				}else{
					echo  "<script>alert('即将进入平台1！');window.location.href='http://101.201.148.160/car/index.php/Admin/Factory/factory_list'</script>";
				}
			}else if($fondd == 2){
				echo  "<script>alert('即将进入平台2！');window.location.href='http://182.92.11.136/carcar/car/index.php/Admin/Factory/factory_list'</script>";
			}else{
				echo "cuo";
			}
		
		
	}

	public function logout()
	{
		session('uid',null);
		$this->redirect('login');
	}

}