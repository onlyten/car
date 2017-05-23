<?php
namespace Home\Controller;
use Think\Controller;

class CarController extends Controller {

// 厂商列表
	public function car_factory()
	{
		$m = M('factory');
		$factory = $m->select();
		$m = M('guanggao');
		$guanggao = $m->select();
		$this->assign('factory',$factory);
		$this->assign('guanggao',$guanggao);
		$this->display();
		// dump($factory);
	}

// 车型列表
	public function car_list() {
		$tab_active = '1';
		if (I('get.tab_active')) {
			$tab_active = I('get.tab_active');
		}
		if (!cookie('car_nickname')) {
			if (!I('get.userinfo_json')) {
				$return_url = urlencode('Car/car_list');
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
		}
		$d = D('PriceGuige');
		$map['price_display'] = '1';
		if (I('get.factory_id')) {
			$map['factory_id'] = I('get.factory_id');
		}
		
		if (I('get.car_order') == '1') {
			$price_guige = $d->where($map)->order('priceshijia desc')->select();
			$car_order = '1';
		} else {
			$price_guige = $d->where($map)->order('priceshijia asc')->select();
			$car_order = '0';
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
		$kuanxing = $p->select();
		$this->assign('kuanxing', $kuanxing);
		$this->assign('car_order', $car_order);
		$this->assign('openid', $openid);
		$this->assign('nickname', $nickname);
		$this->assign('tab_active', $tab_active);
		$this->assign('rexiao_car', $rexiao_car);
		$this->assign('pritype', $pritype);
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
			$date = strtotime($price_guige[$i]["dtime"]);
			$cha = $date - $time;
			$price_guige[$i]["dtime"] = (int)($cha / 3600 / 24 + 1);
			}
	$this->assign('price_guige', $price_guige);
		$this->display();
		//echo $price_guige[0]["dtime"];
		// dump($price_guige);
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
			$date = strtotime($price_guige[$i]["dtime"]);
			$cha = $date - $time;
			$price_guige[$i]["dtime"] = (int)($cha / 3600 / 24 + 1);
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
		$dingdan_id = gen_random(11);
		$openid = I('get.openid');
		$price_id = I('get.price_id');
		$nickname = I('get.nickname');
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
		$this->assign('openid', $openid);
		$this->assign('nickname', $nickname);
		$this->display();
		/*dump($price_guige);
	dump($nickname);*/

	}

// 订单购买
	public function car_buy_update() {
		/*dump(I('post.'));
		dump(I('get.'));*/
		$nickname = I('get.nickname');
		$data['user_sum'] = I('post.user_sum');
		$data['user_phone'] = I('post.user_phone');
		$data['openid'] = I('get.openid');
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
		$wx_user_openid = $m->where($wx_user_map)->getField('user_openid', true);

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

				$this->redirect('car_buy_update_success', array('nickname' => $nickname, 'guige_name_ch' => $guige_name_ch, 'user_sum' => $data['user_sum']));
			} else {
				$this->error('购买失败，请重试');
			}
		}

	}

// 购买成功
	public function car_buy_update_success() {
		$nickname = I('get.nickname');
		$guige_name_ch = I('get.guige_name_ch');
		$user_sum = I('get.user_sum');
		$this->assign('nickname', $nickname);
		$this->assign('guige_name_ch', urldecode($guige_name_ch));
		$this->assign('user_sum', $user_sum);
		$this->display();
	}

// 车辆订单
	public function car_dingdan() {
		// 获取openid
		if (!cookie('car_openid')) {
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
		}
		//分页

		if (!I('get.page_now')) {
			$page_now = 1;
		} else {
			$page_now = I('get.page_now');
		}

		$q = M('pritype');
		$pritype = $q->select();
		$this->assign('pritype', $pritype);

		$d = D('DingdanGuige');
		$map['openid'] = $openid;
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
		$dingdan = $m->where('id = ' . $id)->field('price_id,user_sum')->find();
		// dump($dingdan);

		if ($m->where('id = ' . $id)->save($data)) {
			$n = M('price');
			$n->where('id = ' . $dingdan['price_id'])->setInc('surplus', $dingdan['user_sum']);
			$this->success('取消成功', U('car_dingdan'));
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

}