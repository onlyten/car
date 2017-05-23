<?php
namespace Admin\Model;
use Think\Model;
class ZhiwuModel extends Model {
   public $_validate = array(
	   array('zhiwu_name_ch','require','职务名称不能为空'),
	   array('remark','require','描述不能为空'),
     array('zhiwu_name_ch','','该职务已经存在,请重新添加',0,'unique',1),
   );
 }