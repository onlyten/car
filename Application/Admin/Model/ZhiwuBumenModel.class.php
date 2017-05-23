<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class ZhiwuBumenModel extends ViewModel {
   public $viewFields = array(
     'zhiwu'=>array('id','zhiwu_name_ch','remark','admin_or',), 
     'bumen'=>array('id'=>'bumen_id','bumen_name_ch','_on'=>'zhiwu.bumen_id=bumen.id'),
   );
 }