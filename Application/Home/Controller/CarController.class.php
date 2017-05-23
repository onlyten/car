<?php
namespace Home\Controller;
use Think\Controller;

class CarController extends Controller {

// 厂商列表
	public function car_factory()
	{
		$m = M('factory');
		$mmap['rexiao'] = '1';
		$factory1 = $m->where($mmap)->select();
		$factory = $m->select();
		$m = M('guanggao');
		$guanggao = $m->select();
		$p = M('kuanxing');
		$map['kuanxing_display'] = '1';
		$kuanxing = $p->order('kuanxing_weight desc')->where($map)->select();
		$ge = count($kuanxing);
		$this->assign('kuanxing', $kuanxing);
		$this->assign('geshu',$ge);
		$arr = array();
		$long = count($factory);
		for($i=0;$i<$long;$i++){
			$arr[] = $factory[$i]['zimu'];
		}
		$arr = array_unique($arr);
		if (in_array("A", $arr)){
			$this->assign('a','A');
		}
		if (in_array("B", $arr)){
			$this->assign('b','B');
		}
		if (in_array("C", $arr)){
			$this->assign('c','C');
		}
		if (in_array("D", $arr)){
			$this->assign('d','D');
		}
		if (in_array("E", $arr)){
			$this->assign('e','E');
		}
		if (in_array("F", $arr)){
			$this->assign('f','F');
		}
		if (in_array("G", $arr)){
			$this->assign('g','G');
		}
		if (in_array("H", $arr)){
			$this->assign('h','H');
		}
		if (in_array("I", $arr)){
			$this->assign('ii','I');
		}
		if (in_array("J", $arr)){
			$this->assign('j','J');
		}
		if (in_array("K", $arr)){
			$this->assign('k','K');
		}
		if (in_array("L", $arr)){
			$this->assign('l','L');
		}
		if (in_array("M", $arr)){
			$this->assign('m','M');
		}
		if (in_array("N", $arr)){
			$this->assign('n','N');
		}
		if (in_array("O", $arr)){
			$this->assign('o','O');
		}
		if (in_array("P", $arr)){
			$this->assign('p','P');
		}
		if (in_array("Q", $arr)){
			$this->assign('q','Q');
		}
		if (in_array("R", $arr)){
			$this->assign('r','R');
		}
		if (in_array("S", $arr)){
			$this->assign('s','S');
		}
		if (in_array("T", $arr)){
			$this->assign('t','T');
		}
		if (in_array("U", $arr)){
			$this->assign('u','U');
		}
		if (in_array("V", $arr)){
			$this->assign('v','V');
		}
		if (in_array("W", $arr)){
			$this->assign('w','W');
		}
		if (in_array("X", $arr)){
			$this->assign('x','X');
		}
		if (in_array("Y", $arr)){
			$this->assign('y','Y');
		}
		if (in_array("Z", $arr)){
			$this->assign('z','Z');
		}
		// echo $i;
		// die("OK");
		//$this->assign('arr',$arr);
		$this->assign('factory',$factory);
		$this->assign('factory1',$factory1);
		$this->assign('guanggao',$guanggao);
		$this->assign('price_guige',$price_guige);
		$this->display();
		 //dump($guanggao);
	}

	
//具体车型
	public function car_choose()
	{
		$m = M('chexing');
		$map['factory_id'] = I('get.factory_id');
		$chexing = $m->where($map)->select();
		$n = M('Factory');
		$mapp['id'] = I('get.factory_id');
		$factory = $n->where($mapp)->limit(1)->select();
		$this->assign('factory',$factory);
		$this->assign('chexing',$chexing);
		$this->display();
	}

// 车型列表
	public function car_list() {
		$xianshi['price_display'] = '1';
		$zaitu['nowprice'] = I('get.zaitu');
		$jiejie['tejia'] = I('get.tejia');
		$xin['newcar'] = I('get.newcar');
		$chexing_id = I('get.chexing_id');
		$zouzou['chexing_id'] = $chexing_id;
		$tab_active = '1';
		$m = D('PriceGuige');
		if (I('get.factory_id')) {
			$ma = I('get.factory_id');
			$mmaapp['factory_id'] = $ma;
			$map['chexing_id'] = $chexing_id;
			if($chexing_id){
				$gui = $m->order('kuanxing_weight desc')->field('kuanxing_id')->distinct(true)->where($map)->select();
			}else{
				$gui = $m->order('kuanxing_weight desc')->field('kuanxing_id')->distinct(true)->where($mmaapp)->select();
			}
			
			//dump($gui);
			$kuanshu = count($gui);
			//echo $kuanshu;
			$m = M('kuanxing');
			if($chexing_id){
				$kuanxing = $m->order('kuanxing_weight desc')->where($map)->select();
			}else{
				$kuanxing = $m->order('kuanxing_weight desc')->where()->select();
			}
			
			//dump($kuanxing);
			$found = 0;
			for($j=0;$j<4;$j++){
				for($i=0;$i<$kuanshu;$i++){
					if($gui[$i]["kuanxing_id"] == $kuanxing[$j]["id"]){
						$tab_active = $j + 1;
						$found = 1;
						break;
					}
				}

				if ($found == 1)
					break;
			}
		}

		if (I('get.tab_active')) {
			$tab_active = I('get.tab_active');
		}
		
		$d = D('PriceGuige');
		$map['price_display'] = '1';
		if (I('get.factory_id')) {
			$mapp['factory_id'] = I('get.factory_id');
		}
		// echo $tab_active;
		 //die("jj");
		if (I('get.car_order') == '1') {
			if(I('get.chexing_id')){
				$price_guige = $d->where($map)->where($zouzou)->where($xianshi)->order('priceshijia desc')->select();
				$car_order = '1';
			}else{
				$price_guige = $d->where($mapp)->where($xianshi)->order('priceshijia desc')->select();
				$car_order = '1';
			}
			if(I('get.tejia')){
				if(cookie('user_phone')){
					$price_guige = $d->where($map)->where($xianshi)->where($jiejie)->order('priceshijia desc')->select();
					$car_order = '1';
				}else{
					// $this->redirect('Register/user_login');
					$this->redirect('Register/user_login', array('tejiache' => '1'));
				}
			}
			if(I('get.newcar')){
				$price_guige = $d->where($map)->where($xin)->where($xianshi)->order('priceshijia desc')->select();
				$car_order = '1';
			}
			if(I('get.zaitu')){
				$price_guige = $d->where($map)->where($zaitu)->where($xianshi)->order('priceshijia desc')->select();
				$car_order = '1';
			}

		} else {
			if(I('get.chexing_id')){
				$price_guige = $d->where($map)->where($zouzou)->where($xianshi)->order('priceshijia asc')->select();
				$car_order = '0';
			}else{
				$price_guige = $d->where($mapp)->where($xianshi)->order('priceshijia asc')->select();
				$car_order = '0';
			}
			if(I('get.tejia')){
				if(cookie('user_phone')){
					$price_guige = $d->where($map)->where($jiejie)->where($xianshi)->order('priceshijia asc')->select();
					$car_order = '0';
				}else{
					//$this->redirect('Register/user_login');
					$this->redirect('Register/user_login', array('tejiache' => '1'));
				}
			}
			if(I('get.newcar')){
				$price_guige = $d->where($map)->where($xin)->where($xianshi)->order('priceshijia asc')->select();
				$car_order = '0';
			}
			if(I('get.zaitu')){
				$price_guige = $d->where($map)->where($zaitu)->where($xianshi)->order('priceshijia asc')->select();
				$car_order = '0';
			}
		}
		$q = M('pritype');
		$pritype = $q->select();
		 //dump($pritype);
		// 热销商品
		$map['rexiao_ornot'] = '1';
		$rexiao_car = $d->where($map)->limit(3)->select();
		$m = M('factory');
		$factory = $m->select();
		$this->assign('factory', $factory);
		$n = M('chexing');
		$chexing = $n->select();
		$this->assign('chexing', $chexing);
		$p = M('kuanxing');
		$map['kuanxing_display'] = '1';
		$kuanxing = $p->order('kuanxing_weight desc')->where($map)->select();
		$geshu = count($kuanxing);
		$this->assign('geshu',$geshu);
		// dump($geshu);
		// die("OK");
		$this->assign('kuanxing', $kuanxing);
		//dump($kuanxing);
		$this->assign('car_order', $car_order);
		$this->assign('user_phone', I('get.user_phone'));
		$this->assign('nickname', $nickname);
		$this->assign('tab_active', $tab_active);
		$this->assign('rexiao_car', $rexiao_car);
		$this->assign('pritype', $pritype);
		$this->assign('factory_id',I('get.factory_id'));
		$this->assign('tejia',I('get.tejia'));
		$this->assign('newcar',I('get.newcar'));
		$this->assign('zaitu',I('get.zaitu'));
		 //dump($nickname);
		foreach ($price_guige as $key => $value) {
			$new_zifu = '';
			$zifu_item = explode(' ', $value['zifu']);
			foreach ($zifu_item as $k => $v) {
				$item = explode(',', $v);
				if ($item[0] == $value['nowprice']) {
					$now_state = $k;
				}
			}
			 //dump($now_state);
			foreach ($zifu_item as $k => $v) {
				if ($k == 0) {
					continue;
				}
				$item = explode(',', $v);
				if ($k < $now_state) {
					$state = 0;
				} elseif ($k == $now_state) {
					$state = 1;
				} else {
					$state = 2;
				}

				foreach ($pritype as $pri_k => $pri_v) {
					$item_id = "" . $pri_v['id'];
					if ($item[0] == $item_id) {
						$item_type = $pri_v['pritype_name'];
					}
				}

				/*0：按钮不可按
				1：按钮可按、现价
				2：按钮可按、非现价*/
				$new_zifu[$k - 1] = array(
					'price' => $item_type,
					'num' => $item[1],
					'type' => $item[2],
					'state' => $state,
				);
			}
		 //dump($price_guige);
			$price_guige[$key]['zifu'] = $new_zifu;
		}
		
		 //dump($rexiao_car);
		$num = count($price_guige);
		for($i=0;$i<$num;$i++){
			$time = time();
			//echo $price_guige[$i]["dtime"]."<br>";
			if($price_guige[$i]["dtime"]){
				$date = strtotime($price_guige[$i]["dtime"]);
				$cha = $date - $time;
				$price_guige[$i]["dtime"] = (int)($cha / 3600 / 24 + 1);
			}
			}
	//dump($price_guige);
	$j=0;
	$long = count($price_guige);
	for($i=0;$i<$long;$i++){
			if($price_guige[$i]['kuanxing_id'] == $kuanxing[0]['id']){
				$price_guige_zero[$j] = $price_guige[$i];
				$j++;
			}
	}
	//dump ($price_guige_zero);


	$j=0;
	$long = count($price_guige);
	for($i=0;$i<$long;$i++){
			if($price_guige[$i]['kuanxing_id'] == $kuanxing[1]['id']){
				$price_guige_one[$j] = $price_guige[$i];
				$j++;
			}
	}
	//dump ($price_guige_one);

	$j=0;
	$long = count($price_guige);
	for($i=0;$i<$long;$i++){
			if($price_guige[$i]['kuanxing_id'] == $kuanxing[2]['id']){
				$price_guige_two[$j] = $price_guige[$i];
				$j++;
			}
	}
	 // dump ($price_guige_two);
	 // echo "111111111111111111111111111"."<br>/";
	 // dump($pritype);
	 // die("jo");
	$this->assign('price_guige_zero', $price_guige_zero);
	$this->assign('price_guige_one', $price_guige_one);
	$this->assign('price_guige_two', $price_guige_two);
	$this->assign('price_guige', $price_guige);
	$this->assign('chexing_id',$chexing_id);
	// dump($price_guige_zero);
	// echo "<br/><br/><br/><br/>";
	//   dump($price_guige_two);
	// 	 die("ok");
		$this->display();
		//echo $price_guige[0]["dtime"];

		//dump($factory);
	//dump($chexing);
	//dump($kuanxing);
	}

// 车型搜索
	public function car_search() {
		// dump(I('post.'));
		$openid = cookie('car_openid');
		$nickname = cookie('car_nickname');
		if (I('post.factory')) {
			$map['factory_id'] = I('post.factory');
		}
		if (I('post.chexing')) {
			$map['chexing_id'] = I('post.chexing');
		}
		if (I('post.kuanxing')) {
			$map['kuanxing_id'] = I('post.kuanxing');
		}
		if (I('post.baojia')) {
			if (I('post.baojia') != 'all') {
				$map['nowprice'] = I('post.baojia');
			}
		}
		// dump($map);

		$q = M('pritype');
		$pritype = $q->select();
		$this->assign('pritype', $pritype);

		$d = D('PriceGuige');
		$map['price_display'] = '1';
		$price_guige = $d->where($map)->order('priceshijia desc')->select();
		foreach ($price_guige as $key => $value) {
			$new_zifu = '';
			$zifu_item = explode(' ', $value['zifu']);
			foreach ($zifu_item as $k => $v) {
				$item = explode(',', $v);
				if ($item[0] == $value['nowprice']) {
					$now_state = $k;
				}
			}
			// dump($now_state);
			foreach ($zifu_item as $k => $v) {
				if ($k == 0) {
					continue;
				}
				$item = explode(',', $v);
				if ($k < $now_state) {
					$state = 0;
				} elseif ($k == $now_state) {
					$state = 1;
				} else {
					$state = 2;
				}

				foreach ($pritype as $pri_k => $pri_v) {
					$item_id = "" . $pri_v['id'];
					if ($item[0] == $item_id) {
						$item_type = $pri_v['pritype_name'];
					}
				}

				/*0：按钮不可按
				1：按钮可按、现价
				2：按钮可按、非现价*/
				$new_zifu[$k - 1] = array(
					'price' => $item_type,
					'num' => $item[1],
					'type' => $item[2],
					'state' => $state,
				);
			}
			$price_guige[$key]['zifu'] = $new_zifu;
		}
		$num = count($price_guige);
		for($i=0;$i<$num;$i++){
			$time = time();
			//echo $price_guige[$i]["dtime"]."<br>";
			if($price_guige[$i]["dtime"]){
				$date = strtotime($price_guige[$i]["dtime"]);
				$cha = $date - $time;
				$price_guige[$i]["dtime"] = (int)($cha / 3600 / 24 + 1);
			}
			}
		$this->assign('price_guige', $price_guige);
		$this->assign('openid', $openid);
		$this->assign('nickname', $nickname);
		$this->display();
		// dump($price_guige);

	}

	public function chexing_select() {
		$factory_id = I('post.factory_id');
		$m = M('chexing');
		$chexing = $m->where("factory_id = '" . $factory_id . "'")->select();
		$this->ajaxReturn($chexing);
	}

// 订单页
	public function car_buy() {
		if(I('get.user_phone')){
			$user_phone = I('get.user_phone');
		}else{
			$user_phone = cookie('user_phone');
		}
		 $m = M('wx_user');
         if ($m->where("user_phone = '".$user_phone."'")->select())
		{
		$dingdan_id = gen_random(11);
		$price_id = I('get.price_id');
		$d = D('PriceGuige');
		$map['price_display'] = '1';
		$map['price_id'] = $price_id;
		$price_guige = $d->where($map)->select();

		$q = M('pritype');
		$pritype = $q->select();
		$this->assign('pritype', $pritype);

		foreach ($price_guige as $key => $value) {
			$new_zifu = '';
			$zifu_item = explode(' ', $value['zifu']);
			foreach ($zifu_item as $k => $v) {
				$item = explode(',', $v);
				if ($item[0] == $value['nowprice']) {
					$now_state = $k;
				}
			}
			// dump($now_state);
			foreach ($zifu_item as $k => $v) {
				if ($k == 0) {
					continue;
				}
				$item = explode(',', $v);
				if ($k < $now_state) {
					$state = 0;
				} elseif ($k == $now_state) {
					$state = 1;
				} else {
					$state = 2;
				}

				foreach ($pritype as $pri_k => $pri_v) {
					$item_id = "" . $pri_v['id'];
					if ($item[0] == $item_id) {
						$item_type = $pri_v['pritype_name'];
					}
				}

				/*0：按钮不可按
				1：按钮可按、现价
				2：按钮可按、非现价*/
				$new_zifu[$k - 1] = array(
					'price' => $item_type,
					'num' => $item[1],
					'type' => $item[2],
					'state' => $state,
				);
			}
			foreach ($new_zifu as $key => $value) {
				$key = '' . $key;
				if ($key == I('get.key')) {
					// 用户所选报价
					$zifu_now = $value;
				}
			}
			// dump($zifu_now);
			$price_guige[$key]['zifu'] = $new_zifu;
		}
		$this->assign('zifu_now', $zifu_now);
		$this->assign('price_guige', $price_guige);
		$this->assign('dingdan_id', $dingdan_id);
		$this->assign('user_phone', $user_phone);
		$this->display();
		/*dump($price_guige);
	dump($nickname);*/
		}else{
		$this->redirect('Register/user_login');
		}
	}

// 订单购买
	public function car_buy_update() {
		/*dump(I('post.'));
		dump(I('get.'));*/
		$user_phone = I('get.user_phone');
		$data['user_sum'] = I('post.user_sum');
		$data['user_phone'] = I('get.user_phone');
		//$data['openid'] = I('get.openid');
		$data['dingdan_id'] = I('get.dingdan_id');
		$data['price_id'] = I('get.price_id');
		$data['user_price'] = I('post.user_price');
		$data['zongjia'] = $data['user_sum'] * $data['user_price'];
		$data['user_price_type'] = I('post.user_price_type');
		$data['dingdan_time'] = time();
		$data['verify_state'] = '1';
		$d = D('PriceGuige');
		$map['price_display'] = '1';
		$map['price_id'] = $data['price_id'];
		$price_guige = $d->where($map)->select();

				$chexing_name_ch = urlencode($price_guige[0]['chexing_name_ch']);
				$dingdan_id = urlencode($price_guige[0]['pihao']);
		// 发送模板消息
		// dump($nickname);
		$d = D('PriceGuige');
		$price_guige = $d->where('price.id = ' . $data['price_id'])->find();
		// dump($price_guige);
		$data['zifu_history'] = $price_guige['zifu'];
		$m = M('wx_user');
		$wx_user_map = array(
			'user_staff' => '1',
			'user_receive' => '1',
		);
		//$wx_user_openid = $m->where($wx_user_map)->getField('user_openid', true);

		$m = M('dingdan');
		$user_sum = 0 + $data['user_sum'];
		if ($data['user_phone']) {
			if ($m->data($data)->add()) {
				// echo $m->_sql();
				// 发送消息
				// echo model_msg($msg);
				$n = M('price');
				$n->where('id = ' . $data['price_id'])->setDec('surplus', $user_sum);
				$guige_name_ch = urlencode($price_guige[0]['guige_name_ch']);

				foreach ($wx_user_openid as $key => $value) {
					$msg = "{
                           \"touser\":\"" . $value . "\",
                           \"template_id\":\"FeQQ_epDO6SHiFUysLqcw9YHGv9P8ThCuVHwjeG_Tdg\",
                           \"url\":\"\",
                           \"topcolor\":\"#FF0000\",
                           \"data\":{
                                   \"first\": {
                                       \"value\":\"您收到一条新订单提醒\",
                                       \"color\":\"#173177\"
                                   },
                                   \"keyword1\":{
                                       \"value\":\"" . date('Y-m-d H:i:s', $data['dingdan_time']) . "\",
                                       \"color\":\"#173177\"
                                   },
                                   \"keyword2\": {
                                       \"value\":\"" . $price_guige['factory_name_ch'] . "\",
                                       \"color\":\"#173177\"
                                   },
                                   \"keyword3\": {
                                       \"value\":\"" . $nickname . "\",
                                       \"color\":\"#173177\"
                                   },
                                   \"keyword4\": {
                                       \"value\":\"" . $price_guige['year_name_ch'] . $price_guige['kuanxing_name_ch'] . $price_guige['factory_name_ch'] . $price_guige['chexing_name_ch'] . $price_guige['guige_name_ch'] . "\",
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
				$url = "http://101.201.148.160/fasong.php?user_phone=".$user_phone."&chexing=".$chexing_name_ch."&dingdan_id=".$dingdan_id;
				//$url="http://www.baidu.com";
				$ch = curl_init( );
				curl_setopt( $ch,CURLOPT_URL,$url );
				curl_setopt( $ch,CURLOPT_HEADER,0 );
				curl_setopt( $ch,CURLOPT_RETURNTRANSFER,1 );
				$ret= curl_exec( $ch );
				curl_close( $ch );
				//echo $ret;
				$this->redirect('car_buy_update_success', array('user_phone' => $user_phone, 'guige_name_ch' => $guige_name_ch, 'user_sum' => $data['user_sum']));
			} else {
				$this->error('购买失败，请重试');
			}
		}

	}

// 购买成功
	public function car_buy_update_success() {
		$user_phone = I('get.user_phone');
		$guige_name_ch = I('get.guige_name_ch');
		$user_sum = I('get.user_sum');
		$this->assign('user_phone', $user_phone);
		$this->assign('guige_name_ch', urldecode($guige_name_ch));
		$this->assign('user_sum', $user_sum);
		$this->display();
	}

// 车辆订单
	public function car_dingdan() {
		// 获取openid
		//echo $user_phone;
		/*if (!cookie('car_openid')) {
			if (!I('get.userinfo_json')) {
				$return_url = urlencode('Car/car_dingdan');
				$this->redirect('Userinfo/get_userinfo', array('return_url' => $return_url));
			} else {
				$userinfo_json = I('get.userinfo_json');
				$userinfo = json_decode(urldecode(I('get.userinfo_json')));
				$openid = $userinfo->openid;
				$nickname = $userinfo->nickname;
			}
			cookie('car_openid', $openid, 3600);
			cookie('car_nickname', $nickname, 3600);
		} else {
			$openid = cookie('car_openid');
			$nickname = cookie('car_nickname');
		}*/
		//分页
		if(I('get.user_phone')){
			$user_phone = I('get.user_phone');
		}else{
			$user_phone = cookie('user_phone');
		}
		if(count($user_phone)){
		$m = M('wx_user');
		$mmm['user_phone'] = $user_phone;
		$user_id = $m->where($mmm)->limit(1)->select();
		//dump($user_id);
		$user_id = $user_id[0]['id'];
		if (!I('get.page_now')) {
			$page_now = 1;
		} else {
			$page_now = I('get.page_now');
		}

		$q = M('pritype');
		$pritype = $q->select();
		$this->assign('pritype', $pritype);

		$d = D('DingdanGuige');
		$map['user_id'] = $user_id;
		$dingdan = $d->where($map)->limit($page_now * 10)->select();
		foreach ($dingdan as $key => $value) {
			$new_zifu = '';
			$zifu_item = explode(' ', $value['zifu_history']);
			foreach ($zifu_item as $k => $v) {
				$item = explode(',', $v);
				if ($item[0] == $value['user_price_type']) {
					$now_state = $k;
				}
			}
			// dump($now_state);
			foreach ($zifu_item as $k => $v) {
				if ($k == 0) {
					continue;
				}
				$item = explode(',', $v);
				if ($k < $now_state) {
					$state = 0;
				} elseif ($k == $now_state) {
					$state = 1;
				} else {
					$state = 2;
				}

				foreach ($pritype as $pri_k => $pri_v) {
					$item_id = "" . $pri_v['id'];
					if ($item[0] == $item_id) {
						$item_type = $pri_v['pritype_name'];
					}
					if ($value['user_price_type'] == $item_id) {
						$dingdan[$key]['user_price_type'] = $pri_v['pritype_name'];
					}
				}

				/*0：按钮不可按
				1：按钮可按、现价
				2：按钮可按、非现价*/
				$new_zifu[$k - 1] = array(
					'price' => $item_type,
					'num' => $item[1],
					'type' => $item[2],
					'state' => $state,
				);
			}
			$dingdan[$key]['zifu_history'] = $new_zifu;
		}
		$dingdan_count = $d->where($map)->count();


      $d = D('DingdanGuige');
      if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
      $dingdan_guige = $d->where($map)->page($page_now.',10')->select();
      //dump($map);
      //echo $d->_sql();
      $dingdan_guige_count = $d->where($map)->count();
      if ($dingdan_guige_count % 10 == 0) {
            $dingdan_guige_pagenum = (int)($dingdan_guige_count/10);
        }else{
            $dingdan_guige_pagenum = (int)($dingdan_guige_count/10)+1;
        }


		$this->assign('price_guige',$price_guige);
		$this->assign('dingdan_guige',$dingdan_guige);
        $this->assign('page_now',$page_now);
        $this->assign('dingdan_guige_count',$dingdan_guige_count);
        $this->assign('dingdan_guige_pagenum',$dingdan_guige_pagenum);
		$this->assign('dingdan', $dingdan);
		//dump($dingdan);
		$this->assign('dingdan_count', $dingdan_count);
		$this->assign('page_now', $page_now);
		$this->display();
	}else{
		$this->redirect('Register/user_login', array('zhuce' => '1'));
	}
		// dump($dingdan);
	}
	




	// 订单详情页
	public function car_dingdan_detail() {
		$id = I('get.id');

		$q = M('pritype');
		$pritype = $q->select();
		$this->assign('pritype', $pritype);

		$d = D('DingdanGuige');
		$dingdan = $d->where('dingdan.id = ' . $id)->select();
		foreach ($dingdan as $key => $value) {
			$new_zifu = '';
			$zifu_item = explode(' ', $value['zifu_history']);
			foreach ($zifu_item as $k => $v) {
				$item = explode(',', $v);
				if ($item[0] == $value['user_price_type']) {
					$now_state = $k;
				}
			}
			// dump($now_state);
			foreach ($zifu_item as $k => $v) {
				if ($k == 0) {
					continue;
				}
				$item = explode(',', $v);
				if ($k < $now_state) {
					$state = 0;
				} elseif ($k == $now_state) {
					$state = 1;
				} else {
					$state = 2;
				}

				foreach ($pritype as $pri_k => $pri_v) {
					$item_id = "" . $pri_v['id'];
					if ($item[0] == $item_id) {
						$item_type = $pri_v['pritype_name'];
					}
					if ($value['user_price_type'] == $item_id) {
						$dingdan[$key]['user_price_type'] = $pri_v['pritype_name'];
					}
				}

				/*0：按钮不可按
				1：按钮可按、现价
				2：按钮可按、非现价*/
				$new_zifu[$k - 1] = array(
					'price' => $item_type,
					'num' => $item[1],
					'type' => $item[2],
					'state' => $state,
				);
			}
			$dingdan[$key]['zifu_history'] = $new_zifu;
		}

		$dingdan = $dingdan[0];
		$dingdan['dingdan_time'] = date("Y-m-d H:i:s",$dingdan['dingdan_time']);
		//dump($dingdan);
		$this->assign('dingdan', $dingdan);
		$this->display();
		// dump($dingdan);
	}

	// 取消订单
	public function car_dingdan_cancel() {
		$id = I('get.id');
		$data['verify_state'] = '5';
		$m = M('dingdan');
		$dingdan = $m->where('id = ' . $id)->field('price_id,user_sum,user_phone')->find();
		// dump($dingdan);

		if ($m->where('id = ' . $id)->save($data)) {
			$n = M('price');
			$n->where('id = ' . $dingdan['price_id'])->setInc('surplus', $dingdan['user_sum']);
			$this->success('取消成功', U('car_dingdan',array('user_phone' => $dingdan['user_phone'])));
		} else {
			$this->error('取消失败，请重试', U('car_dingdan'));
		}
	}
	public function jianjie() {
		$this->display;
	}


	public function car_peizhi() {
		
		$d = D('PriceGuige');
		if (I('get.price_id')){
			$price_id =I('get.price_id');
			//echo $price_id;
			$map['price_id'] = array('eq',$price_id);
			//dump($map);
			
		}
		$price_guige = $d->where($map)->select();
		//dump($price_guige);
		$this->assign('price_guige', $price_guige);
		$this->assign('price_id', $price_id);
		$this->display();
		
	}


	public function car_xiangqing() {
		$d = D('PriceGuige');
		if (I('get.price_id')){
			$price_id =I('get.price_id');
			//echo $price_id;
			$map['price_id'] = array('eq',$price_id);
			//dump($map);
			
		}
		$price_guige = $d->where($map)->select();
		//dump($price_guige);
		$this->assign('price_guige', $price_guige);
		$this->assign('price_id', $price_id);
		$this->display();
    }




	public function car_more() {
		$zaitu['nowprice'] = I('get.zaitu');
		$jiejie['tejia'] = I('get.tejia');
		$xin['newcar'] = I('get.newcar');
		$chexing_id = I('get.chexing_id');
		$zouzou['chexing_id'] = $chexing_id;
		$tab_active = '1';
		$m = D('PriceGuige');
		if (I('get.tab_active')) {
			$tab_active = I('get.tab_active');
		}
		$d = D('PriceGuige');
		$map['price_display'] = '1';
		if (I('get.factory_id')) {
			$map['factory_id'] = I('get.factory_id');
		}
		//echo $tab_active;
		if (I('get.car_order') == '1') {
			if(I('get.chexing_id')){
				$price_guige = $d->where($map)->where($zouzou)->order('priceshijia desc')->select();
				$car_order = '1';
			}else{
				$price_guige = $d->where($map)->order('priceshijia desc')->select();
				$car_order = '1';
			}
			if(I('get.tejia')){
				$price_guige = $d->where($map)->where($jiejie)->order('priceshijia desc')->select();
				$car_order = '1';
			}
			if(I('get.newcar')){
				$price_guige = $d->where($map)->where($xin)->order('priceshijia desc')->select();
				$car_order = '1';
			}
			if(I('get.zaitu')){
				$price_guige = $d->where($map)->where($zaitu)->order('priceshijia desc')->select();
				$car_order = '1';
			}
		} else {
			if(I('get.chexing_id')){
				$price_guige = $d->where($map)->where($zouzou)->order('priceshijia asc')->select();
				$car_order = '0';
			}else{
				$price_guige = $d->where($map)->order('priceshijia asc')->select();
				$car_order = '0';
			}
			if(I('get.tejia')){
				$price_guige = $d->where($map)->where($jiejie)->order('priceshijia asc')->select();
				$car_order = '0';
			}
			if(I('get.newcar')){
				$price_guige = $d->where($map)->where($xin)->order('priceshijia asc')->select();
				$car_order = '0';
			}
			if(I('get.zaitu')){
				$price_guige = $d->where($map)->where($zaitu)->order('priceshijia asc')->select();
				$car_order = '0';
			}
		}
		$q = M('pritype');
		$pritype = $q->select();
		 //dump($pritype);
		// 热销商品
		$map['rexiao_ornot'] = '1';
		$rexiao_car = $d->where($map)->limit(3)->select();
		$m = M('factory');
		$factory = $m->select();
		$this->assign('factory', $factory);
		$n = M('chexing');
		$chexing = $n->select();
		$this->assign('chexing', $chexing);
		$p = M('kuanxing');
		$map['kuanxing_display'] = '1';
		$kuanxing = $p->order('kuanxing_weight desc')->where($map)->select();
		$geshu = count($kuanxing);
		$this->assign('geshu',$geshu);
		//dump($geshu);
		$this->assign('kuanxing', $kuanxing);
		//dump($kuanxing);
		$this->assign('car_order', $car_order);
		$this->assign('openid', $openid);
		$this->assign('nickname', $nickname);
		$this->assign('tab_active', $tab_active);
		$this->assign('rexiao_car', $rexiao_car);
		$this->assign('pritype', $pritype);
		$this->assign('factory_id',I('get.factory_id'));
		 //dump($nickname);
		foreach ($price_guige as $key => $value) {
			$new_zifu = '';
			$zifu_item = explode(' ', $value['zifu']);
			foreach ($zifu_item as $k => $v) {
				$item = explode(',', $v);
				if ($item[0] == $value['nowprice']) {
					$now_state = $k;
				}
			}
			 //dump($now_state);
			foreach ($zifu_item as $k => $v) {
				if ($k == 0) {
					continue;
				}
				$item = explode(',', $v);
				if ($k < $now_state) {
					$state = 0;
				} elseif ($k == $now_state) {
					$state = 1;
				} else {
					$state = 2;
				}

				foreach ($pritype as $pri_k => $pri_v) {
					$item_id = "" . $pri_v['id'];
					if ($item[0] == $item_id) {
						$item_type = $pri_v['pritype_name'];
					}
				}

				/*0：按钮不可按
				1：按钮可按、现价
				2：按钮可按、非现价*/
				$new_zifu[$k - 1] = array(
					'price' => $item_type,
					'num' => $item[1],
					'type' => $item[2],
					'state' => $state,
				);
			}
		 //dump($price_guige);
			$price_guige[$key]['zifu'] = $new_zifu;
		}
		
		 //dump($rexiao_car);
		$num = count($price_guige);
		for($i=0;$i<$num;$i++){
			$time = time();
			//echo $price_guige[$i]["dtime"]."<br>";
			if($price_guige[$i]["dtime"]){
				$date = strtotime($price_guige[$i]["dtime"]);
				$cha = $date - $time;
				$price_guige[$i]["dtime"] = (int)($cha / 3600 / 24 + 1);
			}
			}
	//dump($price_guige);
	$j=0;
	$long = count($price_guige);
	for($i=0;$i<$long;$i++){
			if($price_guige[$i]['kuanxing_id'] == $kuanxing[3]['id']){
				$price_guige_three[$j] = $price_guige[$i];
				$j++;
			}
	}
	$this->assign('price_guige_three', $price_guige_three);
	$this->assign('price_guige', $price_guige);
		$this->display();
		//echo $price_guige[0]["dtime"];
		// dump($price_guige);
		//dump($factory);
	//dump($chexing);
	//dump($kuanxing);
	}
}