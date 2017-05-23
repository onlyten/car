<?php
namespace Admin\Model;
use Think\Model;
class KuanxingModel extends Model {
   public $_validate = array(
	   array('kuanxing_name_ch','require','款型名称不能为空'),
	   array('kuanxing_weight','require','排序不能为空'),
     array('kuanxing_name_ch','','该款型名称已存在，请重新添加',0,'unique',1),
			array('kuanxing_weight','number','排序输入的不是数字'),
   );
 }