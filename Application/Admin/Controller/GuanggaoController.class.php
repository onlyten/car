<?php
namespace Admin\Controller;
use Think\Controller;
class GuanggaoController extends Controller {
    public function guanggao_list(){
		//商品列表
    
        $m = M('guanggao');
        if (I('get.search_condition')) {
            $search_condition = I('search_condition');
            $map['guanggao_name'] = array('like','%'.$search_condition.'%');//商品名模糊查找
        }
        
         //dump($map);
        if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
        $guanggao = $m->order('guanggao_weight desc')->where($map)->page($page_now.',10')->select();
         //echo $m->_sql();
        $guanggao_count = $m->where($map)->count();
        if ($guanggao_count % 10 == 0) {
            $guanggao_pagenum = (int)($guanggao_count/10);
        }else{
            $guanggao_pagenum = (int)($guanggao_count/10)+1;
        }
      //echo "dfgv";
        //dump($guanggao);
		$geshu = count($guanggao);
		$this->assign('geshu',$geshu);
        $this->assign('guanggao',$guanggao);
        $this->assign('page_now',$page_now);
        $this->assign('guanggao_count',$guanggao_count);
        $this->assign('guanggao_pagenum',$guanggao_pagenum);
        $this->assign('search_condition',$search_condition);
        $this->display();
    }
    

    public function guanggao_add()
    {  //添加商品
        
		$m = M('guanggao');
		$guanggao = $m->select();
		$this->assign('guanggao',$guanggao);
        $this->display();

         
    }

    public function guanggao_add_update()
    {  //添加商品后进行更新
        $data['guanggao_name'] = I('post.guanggao_name');
		$data['guanggao_content'] = I('post.guanggao_content');
         $data['guanggao_weight'] = I('post.guanggao_weight');
         $data['guanggao_lianjie'] = I('post.guanggao_lianjie');
      
      
        $img_filename = gen_random();
        $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
        $upload_path = './Public/upload/img/';
        move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
        if ($_FILES["picurl"]['size'] != 0) {
            $data['guanggao_img'] = $img_filename.$ext;
        }
        // dump($data);
         
         
        
            $m = M('guanggao');
            if ($m->data($data)->add()) {
               $this->success('添加成功',U('guanggao_list'));
            }else{
                $this->error('添加失败，请重试',U('guanggao_list'));
            }
        
        
    }

    public function guanggao_delete_update()
    {  //删除商品后更新
        $delete_id = I('post.delete_id');
        // dump($delete_id);
        $map['id']  = array('in',$delete_id);
        $m = M('guanggao');
        if ($m->where($map)->delete()) {
            $this->success('删除成功',U('guanggao_list'));
        }else{
            $this->error('删除失败，请重试',U('guanggao_list'));
        }
    }

    public function guanggao_modify()
    {  //对商品进行修改
		
        $guanggao_id = I('get.guanggao_id');
        $m = M('guanggao');
        $guanggao = $m->where('id = '.$guanggao_id)->limit(1)->select();
         //dump($guanggao);
        $this->assign('guanggao',$guanggao);
        $this->display();
    }

    public function guanggao_modify_update()
    {  //对商品修改之后进行更新
         //dump(I('get.'));
        $guanggao_id = I('get.guanggao_id');
        $m = M('guanggao');
       
        $data['guanggao_name'] = I('post.guanggao_name');
        $data['guanggao_content'] = I('post.guanggao_content');
        $data['guanggao_weight'] = I('post.guanggao_weight');
        $data['guanggao_lianjie'] = I('post.guanggao_lianjie');

          if ($_FILES["picurl"]['size'] != 0) {
            $img_filename = gen_random();
            $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
            $upload_path = './Public/upload/img/';
            move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
            $data['guanggao_img'] = $img_filename.$ext;
        }
       
      
        $m = M('guanggao');
        if ($m->where('id = '.$guanggao_id)->save($data)) {
            $this->success('修改成功',U('guanggao_list'));
        }else{
            $this->error('修改失败，请重试',U('guanggao_list'));
        }

    }
}
