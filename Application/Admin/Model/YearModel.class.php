<?php
namespace Admin\Model;
use Think\Model;
class YearModel extends Model {
   public $_validate = array(
	   array('year_name_ch','require','年款名称不能为空'),
	   array('year_weight','require','排序不能为空'),
     array('year_name_ch','','该年款已存在，请重新添加',0,'unique',1),
			array('year_weight','number','排序输入的不是数字'),
   );
 }