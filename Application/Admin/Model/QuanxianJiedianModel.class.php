<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class QuanxianJiedianModel extends ViewModel {
   public $viewFields = array(
     'staff'=>array('id'=>'uid'),
     'quanxian'=>array('jiedian_id','_on'=>'quanxian.zhiwu_id=staff.zhiwu_id'),
     'jiedian'=>array('jiedian_url','_on'=>'quanxian.jiedian_id=jiedian.id'),
   );
 }
