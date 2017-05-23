<?php
namespace Admin\Controller;
use Think\Controller;
class DingdanController extends CommonController {
    // 订单列表
/*
    1：审核中
    2：订单待付款
    3：付款中
    4：确认收款订单成功
    5：订单失败
    */
    public function dingdan_list()
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

    $m = M('chexing');
    $chexing = $m->select();
    $this->assign('chexing',$chexing);

    $m = M('year');
    $year = $m->select();
    $this->assign('year',$year);

    $d = D('DingdanGuige');
    $dingdan_guige = $d->select();
    
/*
    dump($dingdan_guige);
    $h = count($dingdan_guige);

    for($i=0;$i<$h;$i++){
      echo $i;
    $dingdan_guige[$i]['zongjia'] = $dingdan_guige[$i]['user_sum'] * $dingdan_guige[$i]['user_price'];
    echo $dingdan_guige[$i]['zongjia'];
    $d->where('id = '.$price_id)->add($dingdan_guige[i]);
    }
*/

        if (I('post.factory_id')) {
            $map['factory_id'] = I('post.factory_id');
        }

        if (I('post.chexing_id')) {
            $map['chexing_id'] = I('post.chexing_id');
        }

        if (I('post.year_id')) {
            $map['year_id'] = I('post.year_id');
        }

        

        if (I('post.verify_state')) {
            $map['verify_state'] = I('post.verify_state');
        }

        if (I('post.user_phone')) {
            $map['user_phone'] = array('like','%'.I('post.user_phone').'%');
        }

       if(I('wx_user_openid')){
       $wx_user_user_openid = I('wx_user_openid');
       $map['openid'] = array('eq',$wx_user_user_openid);

       if (I('post.guige_id')) {
            $map['guige_id'] = I('post.guige_id');
        }
    }
        //分页

      if (!I('get.page_now')) {
            $page_now = 1;
        }else{
            $page_now = I('get.page_now');
        }
      $dingdan_guige = $d->where($map)->page($page_now.',10')->select();
      echo $d->_sql();
      $dingdan_guige_count = $d->where($map)->count();

      dump($map);
      echo '111';
      if ($dingdan_guige_count % 10 == 0) {
            $dingdan_guige_pagenum = (int)($dingdan_guige_count/10);
        }else{
            $dingdan_guige_pagenum = (int)($dingdan_guige_count/10)+1;
        }
    $h = count($dingdan_guige);
    //echo $h;
    for($i=0;$i<$h;$i++){
      $arr_baojia = explode(' ',$dingdan_guige[$i]['zifu']);
      //dump($dingdan_guige[$i]);
        foreach($arr_baojia as $k=>$v){
          //echo "<br> one price " . $v . "<br>"; 


          $arrd = explode(',',$v);
          //dump($arrd);
          //dump($dingdan_guige[$i]['user_price_type']);
            //echo"<br> abd " . $dingdan_guige[$i]['user_price_type'] . " " . $arrd[0] . "<br>";
            if($dingdan_guige[$i]['user_price_type']==$arrd[0]){
                  //dump($dingdan_guige[$i]['user_price']);
              $dingdan_guige[$i]['user_price'] = $dingdan_guige[$i]['user_price'].万.$arrd[2];
                  
                // echo "<br> user price " .  $dingdan_guige[$i]['user_price'] . "<br>";
                // echo "$arrd[2] <br>";
              }
        }
    }
      $this->assign('dingdan_guige',$dingdan_guige);
      $this->assign('page_now',$page_now);
        $this->assign('dingdan_guige_count',$dingdan_guige_count);
        $this->assign('dingdan_guige_pagenum',$dingdan_guige_pagenum);
    $this->assign('zongjia',$zongjia);
      $this->display();
       // dump(I('post.'));
       // dump($map);
      //dump($dingdan_guige);
    }

// 删除更新
    public function dingdan_delete_update()
    {
        $delete_id = I('post.delete_id');
        // dump($delete_id);
        $map['id']  = array('in',$delete_id);
        $m = M('dingdan');
        if ($m->where($map)->delete()) {
            $this->success('删除成功',U('dingdan_list'));
        }else{
            $this->error('删除失败，请重试',U('dingdan_list'));
        }
    }

    // 修改审核状态
    public function dingdan_verify_update()
    {
        /*1：审核中
        2：订单待付款
        3：付款中
        4：确认收款订单成功
        5：订单失败 */
        if (I('post.verify_state')) {
            $data['verify_state'] = I('post.verify_state');
            $id = I('get.id');
            $m = M('dingdan');
            $dingdan = $m->where('id = '.$id)->find();
            // dump($dingdan);
            switch ($dingdan['verify_state']) {
                case '1':
                    $pre_state = '审核中';
                    break;
                case '2':
                    $pre_state = '订单待付款';
                    break;
                case '3':
                    $pre_state = '付款中';
                    break;
                case '4':
                    $pre_state = '确认收款订单成功';
                    break;
                case '5':
                    $pre_state = '订单失败';
                    break;
            }
            switch ($data['verify_state']) {
                case '1':
                    $now_state = '审核中';
                    break;
                case '2':
                    $now_state = '订单待付款';
                    break;
                case '3':
                    $now_state = '付款中';
                    break;
                case '4':
                    $now_state = '确认收款订单成功';
                    break;
                case '5':
                    $now_state = '订单失败';
                    break;
            }
            //echo $pre_state;
           // echo $now_state;
            $d = D('PriceGuige');
            $price_guige = $d->where('price.id = '.$dingdan['price_id'])->find();
            // dump($price_guige);
            // 组装模板消息
            $msg =  "{
                       \"touser\":\"".$dingdan['openid']."\",
                       \"template_id\":\"uvQ6PzFCGAvh-IByzEUhaPEvFnVOkWrO0AK1O4cVBi4\",
                       \"url\":\"\",
                       \"topcolor\":\"#FF0000\",
                       \"data\":{
                               \"first\": {
                                   \"value\":\"您的订单状态已被更改\",
                                   \"color\":\"#173177\"
                               },
                               \"keyword1\":{
                                   \"value\":\"".$price_guige['year_name_ch'].$price_guige['kuanxing_name_ch'].$price_guige['factory_name_ch'].$price_guige['chexing_name_ch'].$price_guige['guige_name_ch']."\",
                                   \"color\":\"#173177\"
                               },
                               \"keyword2\": {
                                   \"value\":\"".$dingdan['user_sum']."\",
                                   \"color\":\"#173177\"
                               },
                               \"keyword3\": {
                                   \"value\":\"".$dingdan['user_sum']*$dingdan['user_price']."万 （".$dingdan['user_price_type']."）\",
                                   \"color\":\"#173177\"
                               },
                               \"keyword4\": {
                                   \"value\":\"".date('Y-m-d H:i:s',$dingdan['dingdan_time'])."\",
                                   \"color\":\"#173177\"
                               },
                               \"keyword5\": {
                                   \"value\":\"您的订单状态已被管理员由 '".$pre_state."' 改为 '".$now_state."'\",
                                   \"color\":\"#173177\"
                               },
                               \"remark\":{
                                   \"value\":\"如有疑问请联系我们,".C('WX_PHONE')."\",
                                   \"color\":\"#173177\"
                               }
                        }
                    }";
                    // echo $msg;
            model_msg($msg);

            // 修改订单数量
            if ($data['verify_state'] == '5') {
              $n = M('price');
              $n->where('id = '.$dingdan['price_id'])->setInc('surplus',$dingdan['user_sum']);
            }

            if ($m->where('id = '.$id)->save($data)) {
                $this->success('审核成功',U('dingdan_list'));
            }else{
                $this->error('审核失败，请重试',U('dingdan_list'));
            }
        }
    }

    public function dingdan_output()
    {
      $d = D('DingdanGuige');
      $dingdan_guige = $d->select();
     
      // dump($erweima_record);
      foreach ($dingdan_guige as $key => $value) {
          $data[$key]['key'] = $key+1;
          $data[$key]['username'] = $value['user_name'];
          $data[$key]['user_phone'] = $value['user_phone'];
          $data[$key]['pihao'] = $value['pihao'];
          $data[$key]['factory_name_ch'] = $value['factory_name_ch'];
          $data[$key]['chexing_name_ch'] = $value['chexing_name_ch'];
          $data[$key]['peizhi'] = $value['peizhi'];
          $data[$key]['color'] = $value['color'];
          $data[$key]['user_sum'] = $value['user_sum'];
          $data[$key]['user_price'] = $value['user_price'];
          $data[$key]['zongjia'] = $value['zongjia'];
          switch ($value['verify_state']) {
            case '1':
              $data[$key]['verify_state'] = '审核中';
              break;
            case '2':
              $data[$key]['verify_state'] = '订单待付款';
              break;
            case '3':
              $data[$key]['verify_state'] = '付款中';
              break;
            case '4':
              $data[$key]['verify_state'] = '确认收款订单成功';
              break;
            case '5':
              $data[$key]['verify_state'] = '订单失败';
              break;
          }
          
      }
              $headArr[]='订单id';
          
              $headArr[]='用户名';
          
              $headArr[]='手机号';
          
              $headArr[]='订单批号';
          
              $headArr[]='品牌';

              $headArr[]='车型';
          
              $headArr[]='配置';
          
              $headArr[]='颜色';
          
              $headArr[]='台数';
          
              $headArr[]='购买价格';

              $headArr[]='总价';
          
              $headArr[]='用户订单状态';
        

      
      $filename = "订单列表";
      // 不能瞎输出，不出结果
      /*dump($filename);
      dump($headArr);
      dump($data);*/
      $this->excel_export($filename,$headArr,$data);
    }

    //excel导出
    public function excel_export($fileName,$headArr,$data)
    {
        //导入PHPExcel类库
        import("Vendor.excel.PHPExcel");
        import("Vendor.excel.PHPExcel.Writer.Excel5");
        import("Vendor.excel.PHPExcel.IOFactory.php");

        $date = date("Y_m_d",time());
        $fileName .= "_{$date}.xls";

        //创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();

         //设置表头
        $key = ord("A");
        //print_r($headArr);exit;
        foreach($headArr as $v){
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }

        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();

        //print_r($data);exit;
        foreach($data as $key => $rows){ //行写入
            $span = ord("A");
            foreach($rows as $keyName=>$value){// 列写入
                $j = chr($span);
                $objActSheet->setCellValue($j.$column, $value);
                $span++;
            }
            $column++;
        }

        $fileName = iconv("utf-8", "gb2312", $fileName);
        //重命名表
        //$objPHPExcel->getActiveSheet()->setTitle('test');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;

    }

}