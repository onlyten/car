<?php
namespace Admin\Model;
use Think\Model;
class JiedianModel extends Model {
	// 自动验证
   protected $_validate = array(
     array('jiedian_title','require','节点名称必须填写！'), 
     array('jiedian_url','require','节点url必须填写！'), 
     array('jiedian_url','','该节点已经存在，请不要重复添加',1,'unique',3), 
   );
 }
