<?php
namespace Admin\Model;
use Think\Model;
class OilModel extends Model {
   public $_validate = array(
	   array('oil_name_ch','require','燃油类型名称不能为空'),
	   array('oil_weight','require','排序不能为空'),
     array('oil_name_ch','','该燃油类型已存在，请重新添加',0,'unique',1),
			array('oil_weight','number','排序输入的不是数字'),
   );
 }