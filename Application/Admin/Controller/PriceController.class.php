<?php
namespace Admin\Controller;
use Think\Controller;
class PriceController extends Controller {
    public function price_list(){
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
        if (I('post.search_condition5') != "空" && I('post.search_condition5') != "" ) {
            $search_condition5 = I('search_condition5');
            $map['pihao'] = array('like', '%' . $search_condition5 . '%'); //批号模糊查找
        }
       // dump($map);
        //dump($search_condition);
        // dump($map);
//激活下一报价  判断并更新
if(I('price_id')){
	$price_id = I('get.price_id');
	$m = M('price');
	$price = $m->where('id = '.$price_id)->select();
	//dump($price);
	$found = 0;
	$arr_baojia = explode(' ',$price[0]['zifu']);
		foreach($arr_baojia as $k=>$v){
			$arrd = explode(',',$v);
			foreach ($arrd as $key => $value) {
					if($price[0]['nowprice'] == $arrd[0]){
							$found = 1;
							break;
					}
			}
			if($found == 1){
				if (($k + 1) == count($arr_baojia))
					break;
				$arrc = explode(',',$arr_baojia[++$k]);
			    $price[0][nowprice] = $arrc[0];
			     $price[0][priceshijia] = $arrc[1];
			    break;
			}
	   }
	   $price = $m->where('id = '.$price_id)->save($price[0]);
}

//$price = $m->where('id = '.$price_id)->save($price[0]);

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



		$m = D('PriceFactory');
        if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
        //echo $page_now;
        //dump($map);
        $price = $m->order('id desc')->where($map)->page($page_now.',10')->select();
         //echo $m->_sql();
        // dump($price);
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
        
		//var_dump($pritype);
		
		foreach ($price as $key => $value) {
			foreach ($pritype as $key_t => $value_t) {
			    if ($value['nowprice'] == $value_t['id']) {
                    $price[$key]['nowprice'] = $value_t['pritype_name'];
                }
			}
        }
        $this->assign('pritype',$pritype);
        //dump($nowprice);
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
		$this->assign('search_condition5',$search_condition5);
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
		$nn = M('factory');
		$map['id'] = I('post.factory_id');
		$factory_name = $nn->where($map)->find();
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
		$data['peizhi'] = I('peizhi');
		$data['color'] = I('color');
		$data['nowprice'] = I('nowprice');
		$data['pricedao'] = I('pricedao');
		$data['priceli'] = I('priceli');
		$data['pricezai'] = I('pricezai');
		$data['priceqi'] = I('priceqi');
		$data['wangzhi'] = I('wangzhi');
		$data['baojia'] = I('baojia');
		$data['tianone'] = I('tianone');
		$data['tiantwo'] = I('tiantwo');
		$data['jiaone'] = I('jiaone');
		$data['jiatwo'] = I('jiatwo');
		$e = I('post.xiangqing');
		$h = html_entity_decode("$e");
		$j = str_replace("\\","",$h);
		$data['xiangqing'] = $j;
		$data['huobi1'] = I('huobi1');
		$data['huobi2'] = I('huobi2');
		$data['tejia'] = I('tejia');
		$data['newcar'] = I('newcar');

		
		
        $data['price_display'] = I('post.price_display');
        
    		
       
        	$img_filename = gen_random();
	        $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
	        $upload_path = './Public/upload/img/'.$factory_name['factory_name_py'].'/';
	        echo $upload_path.$img_filename.$ext."<br/>";
	        move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
	        if ($_FILES["picurl"]['size'] != 0) {
	            $data['car_img'] = $factory_name['factory_name_py'].'/'.$img_filename.$ext;
	        }
	        

			$img_filename = gen_random();
	        $ext = substr($_FILES['pic']['name'], strpos($_FILES['pic']['name'],'.'), strlen($_FILES['pic']['name'])-1);//拿到后缀
	        $upload_path = './Public/upload/img/'.$factory_name['factory_name_py'].'/';
	        move_uploaded_file($_FILES['pic']['tmp_name'],$upload_path.$img_filename.$ext);
	        if ($_FILES["pic"]['size'] != 0) {
	            $data['cara_img'] = $factory_name['factory_name_py'].'/'.$img_filename.$ext;
	        }


			$img_filename = gen_random();
	        $ext = substr($_FILES['picc']['name'], strpos($_FILES['picc']['name'],'.'), strlen($_FILES['picc']['name'])-1);//拿到后缀
	        $upload_path = './Public/upload/img/'.$factory_name['factory_name_py'].'/';
	        move_uploaded_file($_FILES['picc']['tmp_name'],$upload_path.$img_filename.$ext);
	        if ($_FILES["picc"]['size'] != 0) {
	            $data['carb_img'] = $factory_name['factory_name_py'].'/'.$img_filename.$ext;
	        }


			$img_filename = gen_random();
	        $ext = substr($_FILES['piccc']['name'], strpos($_FILES['piccc']['name'],'.'), strlen($_FILES['piccc']['name'])-1);//拿到后缀
	        $upload_path = './Public/upload/img/'.$factory_name['factory_name_py'].'/';
	        move_uploaded_file($_FILES['piccc']['tmp_name'],$upload_path.$img_filename.$ext);
	        if ($_FILES["piccc"]['size'] != 0) {
	            $data['carc_img'] = $factory_name['factory_name_py'].'/'.$img_filename.$ext;
	        }

			$img_filename = gen_random();
	        $ext = substr($_FILES['picccc']['name'], strpos($_FILES['picccc']['name'],'.'), strlen($_FILES['picccc']['name'])-1);//拿到后缀
	        $upload_path = './Public/upload/img/'.$factory_name['factory_name_py'].'/';
	        move_uploaded_file($_FILES['picccc']['tmp_name'],$upload_path.$img_filename.$ext);
	        if ($_FILES["picccc"]['size'] != 0) {
	            $data['card_img'] = $factory_name['factory_name_py'].'/'.$img_filename.$ext;
	        }

        
			$tupian = explode(",",I('post.bc'));
    		$tupian = array_filter($tupian);
    		if($data['car_img'] && $data['cara_img'] && $data['carb_img'] && $data['carc_img'] && !$data['card_img']){
    			$data['card_img'] = $factory_name['factory_name_py'].'/'.$tupian[0];
    		}
    		if($data['car_img'] && $data['cara_img'] && $data['carb_img'] && !$data['carc_img'] && !$data['card_img']){
    			$data['carc_img'] = $factory_name['factory_name_py'].'/'.$tupian[0];
    			$data['card_img'] = $factory_name['factory_name_py'].'/'.$tupian[1];
    		}
    		if($data['car_img'] && $data['cara_img'] && !$data['carb_img'] && !$data['carc_img'] && !$data['card_img']){
    			$data['carb_img'] = $factory_name['factory_name_py'].'/'.$tupian[0];
    			$data['carc_img'] = $factory_name['factory_name_py'].'/'.$tupian[1];
    			$data['card_img'] = $factory_name['factory_name_py'].'/'.$tupian[2];
    		}
    		if($data['car_img'] && !$data['cara_img'] && !$data['carb_img'] && !$data['carc_img'] && !$data['card_img']){
    			$data['cara_img'] = $factory_name['factory_name_py'].'/'.$tupian[0];
    			$data['carb_img'] = $factory_name['factory_name_py'].'/'.$tupian[1];
    			$data['carc_img'] = $factory_name['factory_name_py'].'/'.$tupian[2];
    			$data['card_img'] = $factory_name['factory_name_py'].'/'.$tupian[3];
    		}
    		if(!$data['car_img'] && !$data['cara_img'] && !$data['carb_img'] && !$data['carc_img'] && !$data['card_img']){
    			$data['car_img'] = $factory_name['factory_name_py'].'/'.$tupian[0];
	    		$data['cara_img'] = $factory_name['factory_name_py'].'/'.$tupian[1];
	    		$data['carb_img'] = $factory_name['factory_name_py'].'/'.$tupian[2];
	    		$data['carc_img'] = $factory_name['factory_name_py'].'/'.$tupian[3];
	    		$data['card_img'] = $factory_name['factory_name_py'].'/'.$tupian[4];
    		}
    		


      // dump($data);
	  $f = I('canshu');
	  $e = str_replace("\r\n","<br>","$f");
	  $data['canshu'] = $e;


	  $k = I('peizhi');
	  $q = str_replace("\r\n","<br>","$k");
	  $data['peizhi'] = $q;
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
		$c = $price[0]['canshu'];
			  $v = str_replace("<br>","\r\n","$c");
			  $price[0]['canshu'] = $v;
        
		$f = $price[0]['peizhi'];
	  $e = str_replace("<br>","\r\n","$f");
	  $price[0]['peizhi'] = $e;

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
		$m = M('price');
        $price = $m->where('id = '.$price_id)->limit(1)->select();
        $data['factory_id'] = I('post.factory_id');
        $data['chexing_id'] = I('post.chexing_id');
        $data['year_id'] = I('post.year_id');
		$data['guige_name_ch'] = I('post.guige_name_ch');
		$data['company_id'] = I('post.company_id');
		$data['pritype_name'] = I('post.pritype_name');
		$data['sum'] = I('sum');
		$data['surplus'] = I('sum')-$price[0]['sum']+$price[0]['surplus'];
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
		$data['tianone'] = I('tianone');
		$data['tiantwo'] = I('tiantwo');
		$data['jiaone'] = I('jiaone');
		$data['jiatwo'] = I('jiatwo');
		$data['huobi1'] = I('huobi1');
		$data['huobi2'] = I('huobi2');
		$data['tejia'] = I('tejia');
		$data['newcar'] = I('newcar');
		$e = I('post.xiangqing');
		$h = html_entity_decode("$e");
		$j = str_replace("\\","",$h);
		$data['xiangqing'] = $j;

	
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
	  if ($_FILES["picurl"]['size'] != 0) {
            $img_filename = gen_random();
            $ext = substr($_FILES['picurl']['name'], strpos($_FILES['picurl']['name'],'.'), strlen($_FILES['picurl']['name'])-1);//拿到后缀
            $upload_path = './Public/upload/img/';
            move_uploaded_file($_FILES['picurl']['tmp_name'],$upload_path.$img_filename.$ext);
            $data['car_img'] = $img_filename.$ext;
        }
		if ($_FILES["pic"]['size'] != 0) {
            $img_filename = gen_random();
            $ext = substr($_FILES['pic']['name'], strpos($_FILES['pic']['name'],'.'), strlen($_FILES['pic']['name'])-1);//拿到后缀
            $upload_path = './Public/upload/img/';
            move_uploaded_file($_FILES['pic']['tmp_name'],$upload_path.$img_filename.$ext);
            $data['cara_img'] = $img_filename.$ext;
        }
		if ($_FILES["picc"]['size'] != 0) {
            $img_filename = gen_random();
            $ext = substr($_FILES['picc']['name'], strpos($_FILES['picc']['name'],'.'), strlen($_FILES['picc']['name'])-1);//拿到后缀
            $upload_path = './Public/upload/img/';
            move_uploaded_file($_FILES['picc']['tmp_name'],$upload_path.$img_filename.$ext);
            $data['carb_img'] = $img_filename.$ext;
        }
		if ($_FILES["piccc"]['size'] != 0) {
            $img_filename = gen_random();
            $ext = substr($_FILES['piccc']['name'], strpos($_FILES['piccc']['name'],'.'), strlen($_FILES['piccc']['name'])-1);//拿到后缀
            $upload_path = './Public/upload/img/';
            move_uploaded_file($_FILES['piccc']['tmp_name'],$upload_path.$img_filename.$ext);
            $data['carc_img'] = $img_filename.$ext;
        }
		if ($_FILES["picccc"]['size'] != 0) {
            $img_filename = gen_random();
            $ext = substr($_FILES['picccc']['name'], strpos($_FILES['picccc']['name'],'.'), strlen($_FILES['picccc']['name'])-1);//拿到后缀
            $upload_path = './Public/upload/img/';
            move_uploaded_file($_FILES['picccc']['tmp_name'],$upload_path.$img_filename.$ext);
            $data['card_img'] = $img_filename.$ext;
        }
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
	public function chexing_select()
    {
        $factory_id = I('post.factory_id');
        $m = M('chexing');
        $chexing = $m->where("factory_id = '".$factory_id."'")->select();
        $this->ajaxReturn($chexing);
    }

     public function pic_select()
    {
        $factory_id = I('post.factory_id');
        $m = M('factory');
        $factory = $m->where("id = '".$factory_id."'")->find();




       
				$dir = "./Public/upload/img/".$factory['factory_name_py']."/";
				$i = 0;
				if (is_dir($dir)){
					if ($dh = opendir($dir)){
						while (($file = readdir($dh))!= false){
							if($i>=0){
								if($file != '.' && $file != '..' ){
									$filenames = $filenames.$file."**";
								}
								// $filePath = $dir.$file;
								//$filePath = $dir.$file;
								
							}
							$i++;
						}
						closedir($dh);
					}
				}

				$arr = array_filter(explode("**",$filenames));
				$stringg = "";
				for($j=0;$j<count($arr);$j++){
					$stringg = $stringg.'<div style="float:left;">
			<input name="abc" id="abc" type = "checkbox" value="'.$arr[$j].'">
			<img src="http://101.201.148.160/car/Public/upload/img/'.$factory['factory_name_py'].'/'.$arr[$j].'" style="width:130px;height:85px;"/>&nbsp;&nbsp;&nbsp;
			</div>';
				}

       

        $this->ajaxReturn($stringg);
    }
	public function guige_select()
    {
        $chexing_id = I('post.chexing_id');
        $m = M('guige');
        $guige = $m->where("chexing_id = '".$chexing_id."'")->select();
        $this->ajaxReturn($guige);
    }
}