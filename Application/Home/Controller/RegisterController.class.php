<?php
namespace Home\Controller;
use Think\Controller;
class RegisterController extends Controller {

    public function user_register()
    {
        $this->display();
    }

    public function user_register_update()
    {        
        if (I('post.user_phone')) {
            $data['user_type'] = I('post.user_type');
            $data['user_register_time'] = time();
            $data['company_name'] = I('post.company_name');
            $data['user_name'] = I('post.user_name');
            $data['user_phone'] = I('post.user_phone');
            $data['user_password'] = I('post.user_password');
            $m = M('wx_user');
            $map['user_phone'] = I('post.user_phone');
            $user = $m->where($map)->select();
            if(count($user) != 0){
                $this->error('该手机号已经注册过了,请重新注册！');
            }
            if ($m->data($data)->add()) {
                cookie('user_phone',I('post.user_phone'),86400);
                $this->redirect('user_register_update_success',array('nickname' => $data['user_phone']));
            }else{
                $this->error('注册失败，请重试');
            }
        }
    }

    public function user_register_update_success()
    {
        $nickname = I('get.nickname');
        $this->assign('nickname',$nickname);
        $this->display();
    }



    public function user_login(){
        if(I('get.zhuce')){
            $this->assign('zhuce',I('get.zhuce'));
        }
        if(I('get.tejiache')){
            $this->assign('tejiache',I('get.tejiache'));
        }
        $this->display();
    }

    public function user_login_update(){
        $m = M('wx_user');
        $map['user_phone'] = I('post.user_phone');
        $user = $m->where($map)->limit(1)->select();
        if($user[0]['user_password'] != I('post.user_password')){
            $this->error('登录名或密码错误，请重试');
        }
        if($user[0]['user_password'] == I('post.user_password')){
            cookie('user_phone',I('post.user_phone'),86400);
            if(I('post.zhuce')){
                $this->redirect('Car/car_dingdan',array('user_phone' => I('post.user_phone')));
            }else if(I('post.tejiache')){
                $this->redirect('Car/car_list',array('tejia' => "1"));
            }else{
                $this->redirect('Car/car_list',array('user_phone' => I('post.user_phone')));
            }
         }
            
            // $this->success('添加成功',U('chexing_list'));
        }
        // dump($user);
        // die('ok');
    

        public function user_password()
        {
            $this->display();
        }

        public function user_password_update()
        {        
            if (I('post.user_phone')) {
                $data['user_phone'] = I('post.user_phone');
                $data['user_password'] = I('post.user_password');
                $m = M('wx_user');
                $map['user_phone'] = I('post.user_phone');
                $user = $m->where($map)->select();
                if(count($user) == 0){
                    $this->error('该手机号还未注册，请先注册！');
                }
                $dataa['user_password'] = I('post.user_password');
                if ($m->where($map)->save($dataa)) {
                    // cookie('user_phone',I('post.user_phone'),86400);
                    echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
                    echo  "<script>alert('密码更改成功！');window.location.href='http://101.201.148.160/car/index.php/Home/Register/user_login'</script>";
                }else{
                    $this->error('找回失败，请重试！');
                }
            }
        }

    function request_get($url)
        {
            if (empty($url)) {
                return false;
            }
            //echo "<br> url $url <br>";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
            //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1800);
            $data = curl_exec($ch);
            $info = curl_getinfo($ch);
                // echo "<pre>";
                // print_r($info);
                // echo "</pre>";
                // curl_close($ch);
       //      echo "<br> data $data <br>";
            return $data;
        }

}