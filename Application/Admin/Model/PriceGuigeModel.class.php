<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class PriceGuigeModel extends ViewModel {
   public $viewFields = array(
     'price'=>array('id'=>'price_id','sum','surplus','factory_id','xiangqing','chexing_id','factory_id','pihao','dtime','canshu','peizhi','color','price_display','nowprice','pricedao','priceli','pricezai','huobi1','huobi2','priceqi','priceshijia'),
     'guige'=>array('kuanxing_id','chexing_type', 'guige_name_ch', 'liter', 'guige_display', 'guige_weight', '_on'=>'price.guige_id=guige.id'),
     'kuanxing'=>array('kuanxing_name_ch', 'kuanxing_display', 'kuanxing_weight', '_on'=>'guige.kuanxing_id=kuanxing.id'),
     'year'=>array('year_name_ch', '_on'=>'price.year_id=year.id'),
     'factory'=>array('factory_name_ch', '_on'=>'price.factory_id=factory.id'),
     'chexing'=>array('chexing_name_ch', '_on'=>'price.chexing_id=chexing.id'),
   );
 }
