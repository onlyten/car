<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class DingdanGuigeModel extends ViewModel {
    public $viewFields = array(
		'dingdan'=>array('id','user_sum','zongjia','user_phone','user_price','user_price_type','verify_state','dingdan_id'),
	    'price'=>array('id'=>'price_id','sum','factory_id','chexing_id','factory_id','pihao','dtime','canshu','peizhi','color','price_display','nowprice','pricedao','priceli','pricezai','priceqi','priceshijia','zifu', '_on'=>'price.id=dingdan.price_id'),
	    'guige'=>array('kuanxing_id','chexing_type', 'guige_name_ch', 'liter', 'guige_display', 'guige_weight', '_on'=>'price.guige_id=guige.id'),
	    'kuanxing'=>array('kuanxing_name_ch', 'kuanxing_display', 'kuanxing_weight', '_on'=>'guige.kuanxing_id=kuanxing.id'),
	    'year'=>array('id'=>'year_id','year_name_ch', '_on'=>'price.year_id=year.id'),
	    'factory'=>array('factory_name_ch', '_on'=>'price.factory_id=factory.id'),
	    'chexing'=>array('chexing_name_ch','rexiao_ornot', '_on'=>'price.chexing_id=chexing.id'),
		'wx_user'=>array('user_phone','user_name','_on'=>'dingdan.user_phone=wx_user.user_phone'),
    );
 }
