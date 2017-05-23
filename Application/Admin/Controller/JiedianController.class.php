<?php
namespace Admin\Controller;
use Think\Controller;
class JiedianController extends CommonController {
   // 节点列表
   public function jiedian_list()
   {
      $m = M('jiedian');
      $jiedian = $m->select();
      $this->assign('jiedian',$jiedian);
      $this->display();
   }
	


	//删除节点
	public function jiedian_delete_update()
    {
        $delete_id = I('post.delete_id');
        // dump($delete_id);
        $map['id']  = array('in',$delete_id);
        $m = M('jiedian');
        if ($m->where($map)->delete()) {
            $this->success('删除成功',U('jiedian_list'));
        }else{
            $this->error('删除失败，请重试',U('jiedian_list'));
        }
    }



   // 添加节点
   public function jiedian_add()
   {
      $this->display();
   }
   // 添加节点更新
   public function jiedian_add_update()
   {
      $jiedian = D("Jiedian"); 
      // dump($jiedian->create());
      if (!$jiedian->create()){
         $this->error('添加失败，'.$jiedian->getError(),U('jiedian_list'));
      }else{
         if ($result = $jiedian->add()) {
            $this->success('添加成功',U('jiedian_list'));
         }else{
            $this->error('添加失败，请重试',U('jiedian_list'));
         }
         
      }
   }

}