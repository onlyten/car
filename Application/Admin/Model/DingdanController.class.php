<?php
namespace Admin\Controller;
use Think\Controller;
class DingdanController extends CommonController {
    // 订单列表
/*
    1：审核中
    2：订单待付款
    3：付款中
    4：确认收款订单成功
    5：订单失败
    */
    public function dingdan_list()
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

		$m = M('chexing');
		$chexing = $m->select();
		$this->assign('chexing',$chexing);

		$m = M('year');
		$year = $m->select();
		$this->assign('year',$year);

		$d = D('DingdanGuige');
		$dingdan_guige = $d->select();
		$h = count($dingdan_guige);
		for($i = 0; $i < $h;$i++){
		$dingdan_guige[i]['zongjia'] = $dingdan_guige[1]['user_sum'] * $dingdan_guige[i]['user_price'];
		echo $dingdan_guige[i]['zongjia'];
		}

		
        if (I('post.factory_id')) {
            $map['factory_id'] = I('post.factory_id');
        }

        if (I('post.chexing_id')) {
            $map['chexing_id'] = I('post.chexing_id');
        }

        if (I('post.year_id')) {
            $map['year_id'] = I('post.year_id');
        }

        if (I('post.guige_id')) {
            $map['guige_id'] = I('post.guige_id');
        }

        if (I('post.verify_state')) {
            $map['verify_state'] = I('post.verify_state');
        }

        if (I('post.user_phone')) {
            $map['user_phone'] = array('like','%'.I('post.user_phone').'%');
        }

		if(I('wx_user_openid')){
		   $wx_user_user_openid = I('wx_user_openid');
		   $map['openid'] = array('eq',$wx_user_user_openid);
		}

    	if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
    	$dingdan_guige = $d->where($map)->page($page_now.',10')->select();
    	$dingdan_guige_count = $d->where($map)->count();
    	if ($dingdan_guige_count % 10 == 0) {
            $dingdan_guige_pagenum = (int)($dingdan_guige_count/10);
        }else{
            $dingdan_guige_pagenum = (int)($dingdan_guige_count/10)+1;
        }
    	$this->assign('dingdan_guige',$dingdan_guige);
    	$this->assign('page_now',$page_now);
        $this->assign('dingdan_guige_count',$dingdan_guige_count);
        $this->assign('dingdan_guige_pagenum',$dingdan_guige_pagenum);
    	$this->display();
       // dump(I('post.'));
       // dump($map);
    	//dump($dingdan_guige);
    }

// 删除更新
    public function dingdan_delete_update()
    {
        $delete_id = I('post.delete_id');
        // dump($delete_id);
        $map['id']  = array('in',$delete_id);
        $m = M('dingdan');
        if ($m->where($map)->delete()) {
            $this->success('删除成功',U('dingdan_list'));
        }else{
            $this->error('删除失败，请重试',U('dingdan_list'));
        }
    }

    // 修改审核状态
    public function dingdan_verify_update()
    {
        /*1：审核中
        2：订单待付款
        3：付款中
        4：确认收款订单成功
        5：订单失败 */
        if (I('post.verify_state')) {
            $data['verify_state'] = I('post.verify_state');
            $id = I('get.id');
            $m = M('dingdan');
            $dingdan = $m->where('id = '.$id)->find();
            // dump($dingdan);
            switch ($dingdan['verify_state']) {
                case '1':
                    $pre_state = '审核中';
                    break;
                case '2':
                    $pre_state = '订单待付款';
                    break;
                case '3':
                    $pre_state = '付款中';
                    break;
                case '4':
                    $pre_state = '确认收款订单成功';
                    break;
                case '5':
                    $pre_state = '订单失败';
                    break;
            }
            switch ($data['verify_state']) {
                case '1':
                    $now_state = '审核中';
                    break;
                case '2':
                    $now_state = '订单待付款';
                    break;
                case '3':
                    $now_state = '付款中';
                    break;
                case '4':
                    $now_state = '确认收款订单成功';
                    break;
                case '5':
                    $now_state = '订单失败';
                    break;
            }
            //echo $pre_state;
           // echo $now_state;
            $d = D('PriceGuige');
            $price_guige = $d->where('price.id = '.$dingdan['price_id'])->find();
            // dump($price_guige);
            // 组装模板消息
            $msg =  "{
                       \"touser\":\"".$dingdan['openid']."\",
                       \"template_id\":\"uvQ6PzFCGAvh-IByzEUhaPEvFnVOkWrO0AK1O4cVBi4\",
                       \"url\":\"\",
                       \"topcolor\":\"#FF0000\",
                       \"data\":{
                               \"first\": {
                                   \"value\":\"您的订单状态已被更改\",
                                   \"color\":\"#173177\"
                               },
                               \"keyword1\":{
                                   \"value\":\"".$price_guige['year_name_ch'].$price_guige['kuanxing_name_ch'].$price_guige['factory_name_ch'].$price_guige['chexing_name_ch'].$price_guige['guige_name_ch']."\",
                                   \"color\":\"#173177\"
                               },
                               \"keyword2\": {
                                   \"value\":\"".$dingdan['user_sum']."\",
                                   \"color\":\"#173177\"
                               },
                               \"keyword3\": {
                                   \"value\":\"".$dingdan['user_sum']*$dingdan['user_price']."万 （".$dingdan['user_price_type']."）\",
                                   \"color\":\"#173177\"
                               },
                               \"keyword4\": {
                                   \"value\":\"".date('Y-m-d H:i:s',$dingdan['dingdan_time'])."\",
                                   \"color\":\"#173177\"
                               },
                               \"keyword5\": {
                                   \"value\":\"您的订单状态已被管理员由 '".$pre_state."' 改为 '".$now_state."'\",
                                   \"color\":\"#173177\"
                               },
                               \"remark\":{
                                   \"value\":\"如有疑问请联系我们,".C('WX_PHONE')."\",
                                   \"color\":\"#173177\"
                               }
                        }
                    }";
                    // echo $msg;
            model_msg($msg);

            // 修改订单数量
            if ($data['verify_state'] == '5') {
              $n = M('price');
              $n->where('id = '.$dingdan['price_id'])->setInc('surplus',$dingdan['user_sum']);
            }

            if ($m->where('id = '.$id)->save($data)) {
                $this->success('审核成功',U('dingdan_list'));
            }else{
                $this->error('审核失败，请重试',U('dingdan_list'));
            }
        }
    }

}