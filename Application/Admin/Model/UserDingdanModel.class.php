<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class UserDingdanModel extends ViewModel {
    public $viewFields = array(
		'dingdan'=>array('id','user_sum','user_phone','user_price','user_price_type','verify_state','dingdan_id'),
		'wx_user'=>array('user_openid','_on'=>'dingdan.openid=user_openid'),
		
    );
 }
