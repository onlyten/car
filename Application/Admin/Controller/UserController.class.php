<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends CommonController {
    public function user_list(){
		//电话查询
        $m = M('wx_user');
        if (I('post.search_condition')) {
            $search_condition = I('search_condition');
            $map['user_phone'] = array('eq',$search_condition);
			//dump($map);
        }
		//绑定员工
		if(I('wx_user_id')){
			$wx_user_id = I('wx_user_id');
			if($wx_user_user_staff =="1"){
				echo "djweofijweofjowejfi";
				$data['user_receive'] = '1';
			}
		   $data['user_staff'] = '1';
		   $m->where(' id = '.$wx_user_id)->save($data);
		}
		//绑定推送
		if(I('wx_user_id_rcv')){
		   $wx_user_id = I('wx_user_id_rcv');
			
		   $data['user_receive'] = '1';
		   $m->where(' id = '.$wx_user_id)->save($data);
		}
		//if(I('wx_user_user_openid')){
		//	$wx_user_user_openid = I('wx_user_user_openid');
		//   $data['user_receive'] = '1';
		//   echo "huihiu";
		//   $m->where(' user_openid = '.$wx_user_user_openid)->save($data);
		//}

		//注册时间查询
		if(I('post.start_time') != "" && I('post.end_time') != ""){
			$start_time = I('start_time');
			$start_time=strtotime($start_time);
			$end_time = I('end_time');
			$end_time=strtotime($end_time);
			$map['user_register_time'] = array(array('egt',$start_time),array('elt',$end_time)) ;
			//echo " $end_time </br>";
		
		}
		//是否员工查询
		if (I('post.staff_display')) {
            $staff_display = I('staff_display');
            $map['user_staff'] = array('eq',$staff_display);
			//dump($map);
        }
		if (I('post.staff_display1')) {
            $staff_display1 = I('staff_display1');
            $map['user_staff'] = array('eq',$staff_display1);
			//dump($map);
        }
		


		//dump($map);
        if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
        $user = $m->order('id desc')->where($map)->page($page_now.',10')->select();
        //echo $m->_sql();
		 foreach ($user as $key => $value) {
                $user[$key]['user_register_time']=date('Y-m-d',$value['user_register_time']);
            }
		//dump($start_time);
        $user_count = $m->where($map)->count();
		//echo "abc.$user_count";
        if ($user_count % 10 == 0) {
            $user_pagenum = (int)($user_count/10);
        }else{
            $user_pagenum = (int)($user_count/10)+1;
        }
        $this->assign('user',$user);
        $this->assign('page_now',$page_now);
        $this->assign('user_count',$user_count);
        $this->assign('user_pagenum',$user_pagenum);
        $this->assign('search_condition',$search_condition);
		$this->assign('start_time',$start_time);
		$this->assign('end_time',$end_time);
		//var_dump($this);
		$this->display();
    }
	public function user_delete_update()
    {
        $delete_id = I('post.delete_id');
        // dump($delete_id);
        $map['id']  = array('in',$delete_id);
        $m = M('wx_user');
        if ($m->where($map)->delete()) {
            $this->success('删除成功',U('user_list'));
        }else{
            $this->error('删除失败，请重试',U('user_list'));
        }
    }

    // 个体发送信息
    public function user_msg_single()
    {
    	if (I('post.msg_title')) {
    		$msg_title = I('post.msg_title');
    		$msg_content = I('post.msg_content');
    		$user_openid = I('post.user_openid');
    		// 组装模板消息
    		$msg =  "{
               \"touser\":\"".$user_openid."\",
               \"template_id\":\"dAdDPFZDqmWR--41p1H2_bLsCmWhkyvHR90DD_BZuok\",
               \"url\":\"".C('DOMAIN_ADD').U('Home/Register/user_register')."\",
               \"topcolor\":\"#FF0000\",
               \"data\":{
                       \"first\": {
                           \"value\":\"您收到一条消息\",
                           \"color\":\"#173177\"
                       },
                       \"keyword1\":{
                           \"value\":\"通知\",
                           \"color\":\"#173177\"
                       },
                       \"keyword2\": {
                           \"value\":\"".$msg_title."\",
                           \"color\":\"#173177\"
                       },
                       \"keyword3\": {
                           \"value\":\"".$msg_content."\",
                           \"color\":\"#173177\"
                       },
                       \"remark\":{
                           \"value\":\"如有疑问请联系我们,".C('WX_PHONE')."\",
                           \"color\":\"#173177\"
                       }
               }
            }";
            if (model_msg($msg) == 'ok') {
            	$this->success('发送成功',U('user_list'));
	        }else{
	            $this->error('发送失败，请重试',U('user_list'));
	        }
    	}
    }

    // 批量发送消息
    public function user_msg_group()
    {
      if (I('post.msg_title')) {
          $msg_title = I('post.msg_title');
          $msg_content = I('post.msg_content');
          $wx_user_map['user_receive'] = '1';
        if (I('post.user_type') == 'worker') {
          $wx_user_map['user_staff'] = '1';
        }
        if (I('post.user_type') == 'user') {
          $wx_user_map['user_staff'] = '0';
        }
        $m = M('wx_user');
        $wx_user_openid = $m->where($wx_user_map)->getField('user_openid',true);
        // dump($wx_user_openid);
        foreach ($wx_user_openid as $key => $value) {
            $msg =  "{
                   \"touser\":\"".$value."\",
                   \"template_id\":\"dAdDPFZDqmWR--41p1H2_bLsCmWhkyvHR90DD_BZuok\",
                   \"url\":\"\",
                   \"topcolor\":\"#FF0000\",
                   \"data\":{
                       \"first\": {
                           \"value\":\"您收到一条消息\",
                           \"color\":\"#173177\"
                       },
                       \"keyword1\":{
                           \"value\":\"通知\",
                           \"color\":\"#173177\"
                       },
                       \"keyword2\": {
                           \"value\":\"".$msg_title."\",
                           \"color\":\"#173177\"
                       },
                       \"keyword3\": {
                           \"value\":\"".$msg_content."\",
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
        }
        $this->success('发送成功',U('user_list'));
      }
    }
}