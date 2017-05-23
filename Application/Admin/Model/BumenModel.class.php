<?php
namespace Admin\Model;
use Think\Model;
class BumenModel extends Model {
   public $_validate = array(
	    array('bumen_name_ch','require','部门名称不能为空'),
	   array('bumen_weight','require','排序不能为空'),
		array('bumen_name_ch','','该部门已经存在，请重新添加',0,'unique',1),
	   array('bumen_weight','number','排序输入的不是数字'),
   );
 }