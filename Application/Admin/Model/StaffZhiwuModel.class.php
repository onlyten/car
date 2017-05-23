<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class StaffZhiwuModel extends ViewModel {
   public $viewFields = array(
     'staff'=>array('id','staff_number', 'staff_name','staff_name_ch','staff_sex','creatime','email','account_use',), 
	 'bumen'=>array('id'=>'bumen_id','bumen_name_ch','_on'=>'staff.bumen_id=bumen.id'),
     'zhiwu'=>array('id'=>'zhiwu_id','zhiwu_name_ch','_on'=>'staff.zhiwu_id=zhiwu.id'),
   );
 }