<?php
namespace Home\Controller;
use Think\Controller;
class CarController extends CommonController {

// 车型列表
    public function car_list()
    {
        $tab_active = '1';
        if (I('get.tab_active')) {
            $tab_active = I('get.tab_active');
        }
        if (!cookie('car_openid')) {
            if (!I('get.userinfo_json')) {
                $return_url = urlencode('Car/car_list');
                $this->redirect('Userinfo/get_userinfo', array('return_url' => $return_url));
            }else{
                $userinfo_json = I('get.userinfo_json');
                $userinfo = json_decode(urldecode(I('get.userinfo_json')));
                $openid = $userinfo->openid;
                $nickname = $userinfo->nickname;
            }
            cookie('car_openid',$openid,3600);
            cookie('car_nickname',$nickname,3600);
        }else{
            $openid = cookie('car_openid');
            $nickname = cookie('car_nickname');
        }
       

    	$d = D('PriceGuige');
    	$map['price_display'] = '1';
        if (I('get.car_order') == '1') {
            $price_guige = $d->where($map)->order('priceshijia desc')->select();
            $car_order = '1';
        }else{            
            $price_guige = $d->where($map)->select();
            $car_order = '0';
        }
        // 热销商品
        $map['rexiao_ornot'] = '1';
        $rexiao_car = $d->where($map)->limit(3)->select();
    	$this->assign('price_guige',$price_guige);
    	$m = M('factory');
    	$factory = $m->select();
    	$this->assign('factory',$factory);
    	$n = M('chexing');
    	$chexing = $n->select();
    	$this->assign('chexing',$chexing);
    	$p = M('kuanxing');
    	$kuanxing = $p->select();
    	$this->assign('kuanxing',$kuanxing);
        $this->assign('car_order',$car_order);
        $this->assign('openid',$openid);
        $this->assign('nickname',$nickname);
        $this->assign('tab_active',$tab_active);
        $this->assign('rexiao_car',$rexiao_car);
        // dump($nickname);
    	$this->display();
        // dump($rexiao_car);
    	// dump($price_guige);
    	/*dump($factory);
    	dump($chexing);
    	dump($kuanxing);*/
    }

// 车型搜索
    public function car_search()
    {
        $openid = cookie('car_openid');
        $nickname = cookie('car_nickname');
    	$map['factory_id'] = I('post.factory');
    	$map['chexing_id'] = I('post.chexing');
    	$map['kuanxing_id'] = I('post.kuanxing');
    	$d = D('PriceGuige');
    	$map['price_display'] = '1';
    	$price_guige = $d->where($map)->select();
    	$this->assign('price_guige',$price_guige);
        $this->assign('openid',$openid);
        $this->assign('nickname',$nickname);
    	$this->display();
    	// dump($price_guige);

    }

// 订单页
    public function car_buy()
    {
        $dingdan_id = gen_random(11);
        $openid = I('get.openid');
        $price_id = I('get.price_id');
        $nickname = I('get.nickname');
        $d = D('PriceGuige');
        $map['price_display'] = '1';
        $map['price_id'] = $price_id;
        $price_guige = $d->where($map)->select();
        $this->assign('price_guige',$price_guige);
        $this->assign('dingdan_id',$dingdan_id);
        $this->assign('openid',$openid);
        $this->assign('nickname',$nickname);
        $this->display();
        dump($price_guige);
        dump($nickname);

    }

// 订单购买
    public function car_buy_update()
    {
        /*dump(I('post.'));
        dump(I('get.'));*/
        $nickname = I('get.nickname');
        $data['user_sum'] = I('post.user_sum');
        $data['user_phone'] = I('post.user_phone');
        $data['openid'] = I('get.openid');
        $data['dingdan_id'] = I('get.dingdan_id');
        $data['price_id'] = I('get.price_id');
        $data['user_price'] = I('post.user_price');
        $data['user_price_type'] = I('post.user_price_type');
        $data['dingdan_time'] = time();
        $data['verify_state'] = '1';
        $d = D('PriceGuige');
        $map['price_display'] = '1';
        $map['price_id'] = $data['price_id'];
        $price_guige = $d->where($map)->select();
        // 发送模板消息
        // dump($nickname);
        $d = D('PriceGuige');
        $price_guige = $d->where('price.id = '.$data['price_id'])->find();
        // dump($price_guige);
        $m = M('wx_user');
        $wx_user_map = array(
            'user_staff' => '1',
            'user_receive' => '1'
            );
        $wx_user_openid = $m->where($wx_user_map)->getField('user_openid',true);

        
        $m = M('dingdan');
        $user_sum = 0 + $data['user_sum'];
        if ($data['user_phone']) {
            if ($m->data($data)->add()) {
                // echo $m->_sql();
                // 发送消息
                // echo model_msg($msg);
                $n = M('price');
                $n->where('id = '.$data['price_id'])->setDec('sum',$user_sum);
                $guige_name_ch = urlencode($price_guige[0]['guige_name_ch']);
                foreach ($wx_user_openid as $key => $value) {
                    $msg =  "{
                           \"touser\":\"".$value."\",
                           \"template_id\":\"FeQQ_epDO6SHiFUysLqcw9YHGv9P8ThCuVHwjeG_Tdg\",
                           \"url\":\"\",
                           \"topcolor\":\"#FF0000\",
                           \"data\":{
                                   \"first\": {
                                       \"value\":\"您收到一条新订单提醒\",
                                       \"color\":\"#173177\"
                                   },
                                   \"keyword1\":{
                                       \"value\":\"".date('Y-m-d H:i:s',$data['dingdan_time'])."\",
                                       \"color\":\"#173177\"
                                   },
                                   \"keyword2\": {
                                       \"value\":\"".$price_guige['factory_name_ch']."\",
                                       \"color\":\"#173177\"
                                   },
                                   \"keyword3\": {
                                       \"value\":\"".$nickname."\",
                                       \"color\":\"#173177\"
                                   },
                                   \"keyword4\": {
                                       \"value\":\"".$price_guige['year_name_ch'].$price_guige['kuanxing_name_ch'].$price_guige['factory_name_ch'].$price_guige['chexing_name_ch'].$price_guige['guige_name_ch']."\",
                                       \"color\":\"#173177\"
                                   },
                                   \"remark\":{
                                       \"value\":\"请登录后台尽快处理\",
                                       \"color\":\"#173177\"
                                   }
                           }
                       }";
                       // echo $msg;
                       model_msg($msg);
                }
                
                $this->redirect('car_buy_update_success',array('nickname' => $nickname,'guige_name_ch' =>$guige_name_ch,'user_sum' => $data['user_sum']));
            }else{
                $this->error('购买失败，请重试');
            }
        }
        
    }

// 购买成功
    public function car_buy_update_success()
    {
        $nickname = I('get.nickname');
        $guige_name_ch = I('get.guige_name_ch');
        $user_sum = I('get.user_sum');
        $this->assign('nickname',$nickname);
        $this->assign('guige_name_ch',urldecode($guige_name_ch));
        $this->assign('user_sum',$user_sum);
        $this->display();
    }

// 车辆订单
    public function car_dingdan()
    {
        // 获取openid
        if (!cookie('car_openid')) {
            if (!I('get.userinfo_json')) {
                $return_url = urlencode('Car/car_dingdan');
                $this->redirect('Userinfo/get_userinfo', array('return_url' => $return_url));
            }else{
                $userinfo_json = I('get.userinfo_json');
                $userinfo = json_decode(urldecode(I('get.userinfo_json')));
                $openid = $userinfo->openid;
                $nickname = $userinfo->nickname;
            }
            cookie('car_openid',$openid,3600);
            cookie('car_nickname',$nickname,3600);
        }else{
            $openid = cookie('car_openid');
            $nickname = cookie('car_nickname');
        }

        if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
        $d = D('DingdanGuige');
        $map['openid'] = $openid;
        $dingdan = $d->where($map)->limit($page_now*10)->select();
        $dingdan_count = $d->where($map)->count();
        $this->assign('dingdan',$dingdan);
        $this->assign('dingdan_count',$dingdan_count);
        $this->assign('page_now',$page_now);
        $this->display();
        // dump($dingdan);
    }

    // 订单详情页
    public function car_dingdan_detail()
    {
        $id = I('get.id');
        $d = D('DingdanGuige');
        $dingdan = $d->where('dingdan.id = '.$id)->find();
        $this->assign('dingdan',$dingdan);
        $this->display();
        // dump($dingdan);
    }

    // 取消订单
    public function car_dingdan_cancel()
    {
        $id = I('get.id');
        $data['verify_state'] = '5';
        $m = M('dingdan');
        if ($m->where('id = '.$id)->save($data)) {
            $this->success('取消成功',U('car_dingdan'));
        }else{
            $this->error('取消失败，请重试',U('car_dingdan'));
        }
    }

}