<?php
namespace Admin\Model;
use Think\Model;
class CompanyModel extends Model {
   public $_validate = array(
	   array('company_name_ch','require','企业中文名不能为空'),
	   array('company_introduct','require','企业简介不能为空'),
	   array('company_person','require','联系人不能为空'),
	   array('company_phone','require','电话不能为空'),
	   array('company_address','require','地址不能为空'),
	   array('company_weight','require','排序不能为空'),
     array('company_name_ch','','该企业已经存在,请重新添加！',0,'unique',1),
	   array('company_introduct','','该企业简介已经存在,请重新添加！',0,'unique',1),
	   array('company_phone','','该电话已经存在,请重新添加！',0,'unique',1),
	   array('company_address','','该地址已经存在,请重新添加！',0,'unique',1),
			array('company_weight','number','排序输入的不是数字！'),
   );
 }