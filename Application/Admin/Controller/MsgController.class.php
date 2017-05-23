<?php
namespace Admin\Controller;
use Think\Controller;
class MsgController extends CommonController {
    public function msg_singel()
    {
        // URL置空，则在发送后，点击模板消息会进入一个空白页面（ios），或无法点击（android）。
        $msg =  "{
           \"touser\":\"op7WBs5dXaJgNv3vsV6pmFvT2260\",
           \"template_id\":\"FeQQ_epDO6SHiFUysLqcw9YHGv9P8ThCuVHwjeG_Tdg\",
           \"url\":\"\",
           \"topcolor\":\"#FF0000\",
           \"data\":{
                   \"first\": {
                       \"value\":\"头信息\",
                       \"color\":\"#173177\"
                   },
                   \"keyword1\":{
                       \"value\":\"提交时间\",
                       \"color\":\"#173177\"
                   },
                   \"keyword2\": {
                       \"value\":\"订单类型\",
                       \"color\":\"#173177\"
                   },
                   \"keyword3\": {
                       \"value\":\"客户信息\",
                       \"color\":\"#173177\"
                   },
                   \"keyword4\": {
                       \"value\":\"兴趣车型\",
                       \"color\":\"#173177\"
                   },
                   \"remark\":{
                       \"value\":\"尾信息\",
                       \"color\":\"#173177\"
                   }
           }
       }";
       // dump($msg);
        
       if (model_msg($msg) == 'ok') {
            echo "发送成功";
        }
    }

}