<?php
namespace Admin\Model;
use Think\Model;
class PriceModel extends Model {
   public $_validate = array(
	   array('sum','require','车辆台数不能为空'),
	   array('pihao','require','批号不能为空'),
	   array('canshu','require','主要参数不能为空'),
	   array('peizhi','require','主要配置不能为空'),
	   array('color','require','颜色不能为空'),
			array('sum','number','排序输入的不是数字!'),
   );
 }