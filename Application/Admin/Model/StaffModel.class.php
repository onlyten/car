<?php
namespace Admin\Model;
use Think\Model;
class StaffModel extends Model {
   public $_validate = array(
	   array('staff_number','require','员工id不能为空'),
	   array('staff_name_ch','require','登陆名不能为空'),
	   array('pwds','require','密码不能为空'),
	   array('staff_name','require','真实名字不能为空'),
	   array('email','require','Email不能为空'),
			array('staff_number','','该员工id已经存在,请重新添加！',0,'unique',1),
		array('staff_name_ch','','该登陆名已经存在,请重新添加！',0,'unique',1),
	   array('email','','该Email已经存在,请重新添加！',0,'unique',1),
   );

 }