<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class GuigeFactoryModel extends ViewModel {
   public $viewFields = array(
     'guige'=>array('id','chexing_type','guige_name_ch','liter','guige_display','guige_weight',),
	  'factory'=>array('id'=>'factory_id', 'factory_name_ch','_on'=>'guige.factory_id=factory.id'),
		'chexing'=>array('id'=>'chexing_id', 'chexing_name_ch','_on'=>'guige.chexing_id=chexing.id'),
		  'kuanxing'=>array('kuanxing_name_ch', '_on'=>'guige.kuanxing_id=kuanxing.id'),
		   'year'=>array('id'=>'year_id', 'year_name_ch','_on'=>'guige.year_id=year.id'),
			'oil'=>array('oil_name_ch','_on'=>'guige.oil_id=oil.id'),
   );
 }
