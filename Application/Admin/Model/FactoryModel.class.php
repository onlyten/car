<?php
namespace Admin\Model;
use Think\Model;
class FactoryModel extends Model {
	// 自动验证
   protected $_validate = array(
	   array('factory_name_ch','require','厂商中文名不能为空'),
	   array('factory_name_en','require','厂商英文名不能为空'),
	   array('factory_name_py','require','厂商拼音名不能为空'),
	   array('factory_weight','require','排序不能为空'),
	   array('factory_name_ch','','该厂商中文名已经存在,请重新添加！',0,'unique',1),
	    array('factory_name_en','','该厂商英文名已经存在,请重新添加！',0,'unique',1),
	    array('factory_name_py','','该厂商拼音名已经存在,请重新添加！',0,'unique',1),
	   array('factory_weight','number','排序输入的不是数字！'),
   );
 }
