<?php
namespace Home\Model;
use Think\Model\ViewModel;
class PriceGuigeModel extends ViewModel {
   public $viewFields = array(
     'price'=>array('id'=>'price_id','sum','surplus','newcar','tejia','factory_id','chexing_id','zifu','pihao','dtime','canshu','peizhi','color','wangzhi','price_display','nowprice','pricedao','priceli','tianone','tiantwo','jiaone','jiatwo','xiangqing','pricezai','huobi1','huobi2','car_img','cara_img','carb_img','carc_img','card_img','priceqi','priceshijia'),
     'guige'=>array('kuanxing_id','chexing_type', 'guige_name_ch', 'liter', 'guige_display', 'guige_weight', '_on'=>'price.guige_id=guige.id'),
     'kuanxing'=>array('kuanxing_name_ch','kuanxing_display', 'kuanxing_weight', '_on'=>'guige.kuanxing_id=kuanxing.id'),
     'year'=>array('year_name_ch', '_on'=>'price.year_id=year.id'),
     'factory'=>array('factory_name_ch','factory_img', '_on'=>'price.factory_id=factory.id'),
     'chexing'=>array('chexing_name_ch','chexing_name_en','rexiao_ornot', '_on'=>'price.chexing_id=chexing.id'),
     'pritype'=>array('pritype_name','_on'=>'price.nowprice=pritype.id'),
   );
 }
