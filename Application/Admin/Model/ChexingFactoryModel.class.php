<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class ChexingFactoryModel extends ViewModel {
   public $viewFields = array(
     'chexing'=>array('id','chexing_name_ch', 'chexing_img','guide_price','chexing_display',), 
     'factory'=>array('id'=>'factory_id','factory_name_ch','_on'=>'chexing.factory_id=factory.id'),
   );
 }