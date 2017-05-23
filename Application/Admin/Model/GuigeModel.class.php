<?php
namespace Admin\Model;
use Think\Model;
class GuigeModel extends Model {
   public $_validate = array(
	   array('guige_name_ch','require','规格中文名不能为空'),
	   array('liter','require','排量不能为空'),
	   array('guige_weight','require','排序不能为空'),
     array('guige_name_ch','','该规格中文名已经存在,请重新添加！',0,'unique',1),
			array('guige_weight','number','排序输入的不是数字！'),
   );
 }