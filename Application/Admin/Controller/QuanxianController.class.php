<?php
namespace Admin\Controller;
use Think\Controller;
class QuanxianController extends CommonController {
   public function quanxian_list()
   {
   	echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'<br>';
   	// 动态获取
   	$a = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	// 数据库读取url
   	$b = "http://www.tianyushengda.com/car/index.php/Admin/Quanxian/quanxian_list";

   	$c = str_replace("/","\/",$b);

   	$c = "/^".$c."/";

	echo $c.'<br>';
   	
   	// 正则比较
   	if (preg_match($c,$a)) {
   		echo "1";
   	}else{
   		echo "2";
   	}
   	// echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

   }

   // 权限分配
   public function quanxian_fenpei()
   {
      $zhiwu_id = I('get.zhiwu_id');
      $n = M('quanxian');
      $quanxian = $n->where('zhiwu_id = '.$zhiwu_id)->select();      
      $m = M('jiedian');
      $jiedian = $m->select();
      // 获取节点选中状态
      foreach ($jiedian as $key => $value) {
         $jiedian[$key]['checked_state'] = '0';
         foreach ($quanxian as $k => $v) {
            if ($value['id'] == $v['jiedian_id']) {
               $jiedian[$key]['checked_state'] = '1';
            }
         }
      }
      
      $this->assign('jiedian',$jiedian);
      $this->assign('zhiwu_id',$zhiwu_id);
      $this->display();
      //dump($jiedian);
   }

   // 权限分配更新
   public function quanxian_feipei_update()
   {
      if (I('post.zhiwu_id')) {
         foreach (I('post.fenpei_id') as $key => $value) {
            $dataList[] = array('zhiwu_id'=>I('post.zhiwu_id'),'jiedian_id'=>$value);
         }
         $m = M('quanxian');
         $m->where('zhiwu_id = '.I('post.zhiwu_id'))->delete();
         if ($m->addAll($dataList)) {
            $this->success('分配成功',U('Zhiwu/zhiwu_list'));
         }else{
            $this->error('分配失败，请重试',U('Zhiwu/zhiwu_list'));
         }
            
      }
   }

}
