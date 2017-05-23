<?php
/*namespace Admin\Model;
use Think\Model\ViewModel;
class TestViewModel extends ViewModel {
    public $viewFields = array(
        // 'doctor'=>array('id','openid','doctor_name','doctor_email','doctor_tel','doctor_hospital','doctor_img','doctor_zhicheng','doctor_hobby','doctor_info','doctor_sn','doctor_ornot','doctor_state'),
        // 'hospital'=>array('hospital_name,hospital_address1,hospital_address2','_on'=>'hospital.id=doctor.doctor_hospital'),
    'doctor'=>array('id','doctor_hospital'),
	'hospital'=>array('hospital_name', '_on'=>'doctor.doctor_hospital=hospital.id'),
    );
}*/
namespace Admin\Model;
use Think\Model\ViewModel;
class BlogViewModel extends ViewModel {
   public $viewFields = array(
     /*'Blog'=>array('id','name','title'),
     'Category'=>array('title'=>'category_name', '_on'=>'Blog.category_id=Category.id'),
     'User'=>array('name'=>'username', '_on'=>'Blog.user_id=User.id'),*/
   );
 }
