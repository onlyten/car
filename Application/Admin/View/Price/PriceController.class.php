<?php
namespace Admin\Controller;
use Think\Controller;
class PriceController extends Controller {
    public function price_list(){
		$d = D('PriceFactory');
		$Price_factory = $d->select();
        $m = D('PriceFactory');
        if (I('post.search_condition') != "空" && I('post.search_condition') != "") {
            $search_condition = I('search_condition');
            $map['factory_id'] = array('eq',$search_condition);
        }
		if (I('post.search_condition1') != "空" && I('post.search_condition1') != "" ) {
            $search_condition1 = I('search_condition1');
            $map['chexing_id'] = array('eq',$search_condition1);
        }
		if (I('post.search_condition2') != "空" && I('post.search_condition2') != "" ) {
            $search_condition2 = I('search_condition2');
            $map['year_id'] = array('eq',$search_condition2);
        }
		if (I('post.search_condition3') != "空" && I('post.search_condition3') != "" ) {
            $search_condition3 = I('search_condition3');
            $map['guige_id'] = array('eq',$search_condition3);
        }
		if (I('post.search_condition4') != "空" && I('post.search_condition4') != "" ) {
            $search_condition4 = I('search_condition4');
            $map['company_id'] = array('eq',$search_condition4);
        }
        // dump($map);
//激活下一报价  判断并更新
if(I('price_id')){
	$price_id = I('get.price_id');
	$m = M('price');
	$price = $m->where('id = '.$price_id)->select();
	//dump($price);

/*
		if($price[0]['nowprice']=="期货价"){
			$price[0]['nowprice']="离港价";
			//echo $price[0]['nowprice'];
			$price[0]['priceshijia'] = $price[0]['priceli'];
			//echo $price[0]['priceshijia'];
			$price = $m->where('id = '.$price_id)->save($price[0]);
		}


		if($price[0]['nowprice']=="离港价"){
			$price[0]['nowprice']="在途价";
			//echo $price[0]['nowprice'];
			$price[0]['priceshijia'] = $price[0]['pricezai'];
			//echo $price[0]['priceshijia'];
			$price = $m->where('id = '.$price_id)->save($price[0]);
		}



		if($price[0]['nowprice']=="在途价"){
			$price[0]['nowprice']="到港价";
			//echo $price[0]['nowprice'];
			$price[0]['priceshijia'] = $price[0]['pricedao'];
			//echo $price[0]['priceshijia'];
			$price = $m->where('id = '.$price_id)->save($price[0]);
		}


		if($price[0]['nowprice']=="到港价"){
			$price[0]['nowprice']="期货价";
			//echo $price[0]['nowprice'];
			$price[0]['priceshijia'] = $price[0]['priceqi'];
			//echo $price[0]['priceshijia'];
			$price = $m->where('id = '.$price_id)->save($price[0]);
		}*/
}


		$m = D('PriceFactory');
        if (!I('post.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('post.page_now');
        }
        $price = $m->order('factory_id desc')->where($map)->page($page_now.',10')->select();
        // echo $m->_sql();
        $price_count = $m->where($map)->count();
        if ($price_count % 10 == 0) {
            $price_pagenum = (int)($price_count/10);
        }else{
            $price_pagenum = (int)($price_count/10)+1;
        }
		$m = M('factory');
		$factory = $m->select();
		$this->assign('factory',$factory);

		$m = M('chexing');
		$chexing = $m->select();
		$this->assign('chexing',$chexing);

		$m = M('year');
		$year = $m->select();
		$this->assign('year',$year);

		$m = M('guige');
		$guige = $m->select();
		$this->assign('guige',$guige);
		
		$m = M('company');
		$company = $m->select();
		$this->assign('company',$company);

		//ms 20150728
		$m = M('pritype');
		$pritype = $m->order('pritype_weight desc')->select();
        
		var_dump($pritype);
		echo "ooooo";
		foreach ($price as $key => $value) {
			foreach ($pritype as $key_t => $value_t) {
			    if ($value['nowprice'] == $value_t['id'] {
                    $price[$key]['nowprice'] = $value_t['typename']
                }
			}
        }

        $this->assign('price',$price);
		//dump($price);
        $this->assign('page_now',$page_now);
        $this->assign('price_count',$price_count);
        $this->assign('price_pagenum',$price_pagenum);
        $this->assign('search_condition',$search_condition);
		$this->assign('search_condition1',$search_condition1);
		$this->assign('search_condition2',$search_condition2);
		$this->assign('search_condition3',$search_condition3);
		$this->assign('search_condition4',$search_condition4);
		$this->assign('nowprice',$nowprice);
		$this->display();
    }

    public function price_add()
    {
		$m = M('factory');
		$factory = $m->select();
		$this->assign('factory',$factory);

		$m = M('chexing');
		$chexing = $m->select();
		$this->assign('chexing',$chexing);

		$m = M('year');
		$year = $m->select();
		$this->assign('year',$year);

		$m = M('guige');
		$guige = $m->select();
		$this->assign('guige',$guige);

		$m = M('company');
		$company = $m->select();
		$this->assign('company',$company);

		$m = M('pritype');
		$pritype = $m->order('pritype_weight desc')->select();
		$this->assign('pritype',$pritype);

		$m = M('price');
		$price = $m->select();
		$this->assign('price',$price);
		$this->display();

    }

    public function price_add_update()
    {
		$Price = D("Price"); // 实例化User对象
		if (!$Price->create()) {
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			exit($Price->getError());
		} else {
			// 验证通过 可以进行其他数据操作
		}
        $data['factory_id'] = I('post.factory_id');
        $data['chexing_id'] = I('post.chexing_id');
        $data['year_id'] = I('post.year_id');
		$data['guige_id'] = I('post.guige_id');
		$data['company_id'] = I('post.company_id');
		$data['pritype_id'] = I('post.pritype_id');
		$data['huobi'] = I('huobi');
		$data['sum'] = I('sum');
		$data['surplus'] = I('sum');
		$data['pihao'] = I('pihao');
		$data['dtime'] = I('dtime');
		$data['canshu'] = I('canshu');
		$data['peizhi'] = I('peizhi');
		$data['color'] = I('color');
		$data['nowprice'] = I('nowprice');
		$data['pricedao'] = I('pricedao');
		$data['priceli'] = I('priceli');
		$data['pricezai'] = I('pricezai');
		$data['priceqi'] = I('priceqi');
		$data['wangzhi'] = I('wangzhi');
		$data['baojia'] = I('baojia');
		
		
        $data['price_display'] = I('post.price_display');
        $img_filename = gen_random();
        $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
       // $upload_path = './Public/upload/img/';
       // move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
       // if ($_FILES["picurl"]['size'] != 0) {
       //     $data['factory_img'] = $img_filename.$ext;
      //  }
      // dump($data);
	  
	 //echo "<br> baojia <br>";
	//var_dump($_POST);
	// var_dump($_POST[baojia]);
	// echo "<br> baoajia end <br>";
	$m = M('Price');
	$price = $m->select();
	//echo count($price);
	//$n = count($price)-1;
	$baojia_name = $_POST[typename];
	$baojia_price = $_POST[baojia];
	$baojia_huobi = $_POST[huobi];
	//dump($baojia_price);

    //echo "name here <br>";
	//dump($baojia_name);

	$price_str = "";
	$h = count(($baojia_name));
    for($j=0;$j<$h;$j++){
		 $price_str = $price_str . " " . $baojia_name[$j] . "," . $baojia_price[$j] . "," . $baojia_huobi[$j];
    }  
	
	//echo "<b> $price_str <br>";

	$data['zifu'] = $price_str;

	//foreach($price as $price[0]){
	  //$data['zifu'] =$price[0][pritype_name].",".$price[0][baojia].",".$price[0][huobi];
	//}

	for($j=0;$j<$h;$j++){
		if($baojia_name[$j]==I('nowprice')){
			$data['priceshijia'] = $baojia_price[$j];
		}
	}


            $m = M('price');
            if ($m->data($data)->add()) {
               $this->success('添加成功',U('price_list'));
            }else{
                $this->error('添加失败，请重试',U('price_list'));
            }
        
        
    }

    public function price_delete_update()
    {
        $delete_id = I('post.delete_id');
        // dump($delete_id);
        $map['id']  = array('in',$delete_id);
        $m = M('price');
        if ($m->where($map)->delete()) {
            $this->success('删除成功',U('price_list'));
        }else{
            $this->error('删除失败，请重试',U('price_list'));
        }
    }

    public function price_modify()
    {
		$m = M('factory');
		$factory = $m->select();
		$this->assign('factory',$factory);

		$m = M('chexing');
		$chexing = $m->select();
		$this->assign('chexing',$chexing);

		$m = M('year');
		$year = $m->select();
		$this->assign('year',$year);

		$m = M('guige');
		$guige = $m->select();
		$this->assign('guige',$guige);

		$m = M('company');
		$company = $m->select();
		$this->assign('company',$company);

		$price_id = I('get.price_id');
        $m = M('price');
        $price = $m->where('id = '.$price_id)->limit(1)->select();
        //dump($price);
        $this->assign('price',$price);

		$m = M('pritype');
		$pritype = $m->order('pritype_weight desc')->select();
		$arr_baojia_new = array();
        $arr_baojia = explode(' ',$price[0]['zifu']);
        foreach ($pritype as $key => $value) {
                $find = 0;
        	foreach ($arr_baojia as $k=> $v) {
				$arrd = explode(',',$v);
        		if ($arrd[0] == $value['id']) { //name,num,type
                                
	        		
                                //$pritype[$key]['num'] = $arrd[1];
                                $arr_baojia_new[$key]['id'] = $value['id'];
								$arr_baojia_new[$key]['name'] = $value['pritype_name'];
                                $arr_baojia_new[$key]['num'] = $arrd[1];
                                $arr_baojia_new[$key]['type'] = $arrd[2];
                                $find = 1;
	        	}
        	}

                if ($find == 0) {
                       
                                $arr_baojia_new[$key]['id'] = $value['id'];
                                $arr_baojia_new[$key]['num'] = 0;
                                $arr_baojia_new[$key]['type'] = "人民币";
                        
                }
        	
        }  
		//dump($arr_baojia_new);
		//echo $arrd[1];
		//dump($value['pritype_name']);
		//dump($arr_baojia);
		//dump($arrd);

         $this->assign('pricenum',$arr_baojia_new);

		$this->assign('pritype',$pritype);

        
        $this->display();
    }

    public function price_modify_update()
    {
        // dump(I('get.'));
        $price_id = I('get.price_id');
        $data['factory_id'] = I('post.factory_id');
        $data['chexing_id'] = I('post.chexing_id');
        $data['year_id'] = I('post.year_id');
		$data['guige_name_ch'] = I('post.guige_name_ch');
		$data['company_id'] = I('post.company_id');
		$data['pritype_name'] = I('post.pritype_name');
		$data['sum'] = I('sum');
		$data['pihao'] = I('pihao');
		$data['dtime'] = I('dtime');
		$data['canshu'] = I('canshu');
		$data['peizhi'] = I('peizhi');
		$data['color'] = I('color');
		$data['nowprice'] = I('nowprice');
		$data['priceshijia'] = I('priceshijia');
		$data['pricedao'] = I('pricedao');
		$data['priceli'] = I('priceli');
		$data['pricezai'] = I('pricezai');
		$data['priceqi'] = I('priceqi');
		$data['wangzhi'] = I('wangzhi');
		$data['baojia'] = I('baojia');

	
	$baojia_name = $_POST[typename];
	$baojia_price = $_POST[baojia];
	$baojia_huobi = $_POST[huobi];
	$price_str = "";
	$h = count(($baojia_name));
    for($j=0;$j<$h;$j++){
		 $price_str = $price_str . " " . $baojia_name[$j] . "," . $baojia_price[$j] . "," . $baojia_huobi[$j];
    }  
	
	//echo "<b> $price_str <br>";

	$data['zifu'] = $price_str;

	for($j=0;$j<$h;$j++){
			if($baojia_name[$j]==I('nowprice')){
				$data['priceshijia'] = $baojia_price[$j];
			}
		}

		
        $data['price_display'] = I('post.price_display');
       // if ($_FILES["picurl"]['size'] != 0) {
         //   $img_filename = gen_random();
         //   $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
          //  $upload_path = './Public/upload/img/';
          //  move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
         //   $data['guige_img'] = $img_filename.$ext;
      //  }
        $m = M('price');
        if ($m->where('id = '.$price_id)->save($data)) {
            $this->success('修改成功',U('price_list'));
        }else{
            $this->error('修改失败，请重试',U('price_list'));
        }
    }


	public function price_check() {
		header("Content-Type:text/html;charset=utf-8");
		$price = D("Price"); // 实例化User对象
		if (!$price->create()) {
			// 指定新增数据
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			$this->error($price->getError ());
		}

	}
}