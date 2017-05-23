<?php
namespace Admin\Model;
use Think\Model;
class ChexingModel extends Model {
   public $_validate = array(
	   array('chexing_name_ch','require','车型中文名不能为空'),
	   array('chexing_name_en','require','车型英文名不能为空'),
	   array('chexing_name_py','require','车型拼音名不能为空'),
	   array('chexing_weight','require','排序不能为空'),
     array('chexing_name_ch','','该车型中文名已存在,请重新添加',0,'unique',1),
	   array('chexing_name_en','','该车型英文名已存在,请重新添加',0,'unique',1),
		 array('chexing_name_py','','该车型拼音名已存在,请重新添加',0,'unique',1),
			array('chexing_weight','number','排序输入的不是数字'),
   );
 }