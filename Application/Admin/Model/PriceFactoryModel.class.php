<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class PriceFactoryModel extends ViewModel {
   public $viewFields = array(
     /*'Blog'=>array('id','name','title'),
     'Category'=>array('title'=>'category_name', '_on'=>'Blog.category_id=Category.id'),
     'User'=>array('name'=>'username', '_on'=>'Blog.user_id=User.id'),*/
     /*
	 'chexing'=>array('chexing_name_ch'),
	 'guige'=>array('guige_name_ch'),
	 'year'=>array('year_name_ch'),
	 'company'=>array('company_name_ch'),
     'price'=>array('id','pihao','sum','nowprice','price_display','_on'=>'price.factory_id=factory.id'), */
      'price'=>array('id','pihao','sum','surplus','nowprice','price_display'),
	  'factory'=>array('id'=>'factory_id','factory_name_ch','_on'=>'price.factory_id=factory.id'),
		'chexing'=>array('id'=>'chexing_id','chexing_name_ch','_on'=>'price.chexing_id=chexing.id'),
		  'guige'=>array('id'=>'guige_id','guige_name_ch', '_on'=>'price.guige_id=guige.id'),
		   'year'=>array('id'=>'year_id','year_name_ch','_on'=>'price.year_id=year.id'),
		    'company'=>array('id'=>'company_id','company_name_ch','_on'=>'price.company_id=company.id'),
   );
 }
