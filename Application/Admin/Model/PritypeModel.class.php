<?php
namespace Admin\Model;
use Think\Model;
class PritypeModel extends Model {
   public $_validate = array(
	   array('pritype_name','require','报价类型名称不能为空'),
	   array('pritype_weight','require','排序不能为空'),
     array('pritype_name','','该报价类型已存在，请重新添加',0,'unique',1),
			array('pritype_weight','number','排序输入的不是数字'),
   );
 }