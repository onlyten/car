<?php
namespace Home\Model;
use Think\Model\ViewModel;
class DingdanGuigeModel extends ViewModel {
    public $viewFields = array(
		'dingdan'=>array('id','user_sum','user_phone','user_price','user_price_type','dingdan_time','verify_state','zifu_history','dingdan_id'),
	    'price'=>array('id'=>'price_id','sum','car_img','factory_id','chexing_id','factory_id','pihao','dtime','canshu','peizhi','color','price_display','nowprice','pricedao','priceli','pricezai','priceqi','priceshijia', '_on'=>'price.id=dingdan.price_id'),
	    'guige'=>array('kuanxing_id','chexing_type', 'guige_name_ch', 'liter', 'guige_display', 'guige_weight', '_on'=>'price.guige_id=guige.id'),
	    'kuanxing'=>array('kuanxing_name_ch', 'kuanxing_display', 'kuanxing_weight', '_on'=>'guige.kuanxing_id=kuanxing.id'),
	    'year'=>array('year_name_ch', '_on'=>'price.year_id=year.id'),
	    'factory'=>array('factory_name_ch', '_on'=>'price.factory_id=factory.id'),
	    'chexing'=>array('chexing_name_ch','rexiao_ornot', '_on'=>'price.chexing_id=chexing.id'),
	    'wx_user'=>array('id'=>'user_id','_on'=>'dingdan.user_phone=wx_user.user_phone'),
    );
 }
