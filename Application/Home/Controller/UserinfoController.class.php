<?php
namespace Home\Controller;
use Think\Controller;
class UserinfoController extends Controller {

    // 获取 access_token
    public function get_access_token($code)
    {
        if (cookie('auth_access_token')) {
            $access_token = cookie('auth_access_token');
            // echo "1";
        }else{
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".C('WX_APPID')."&secret=".C('WX_APPSECRET')."&code=".$code."&grant_type=authorization_code";   
            $jsoninfo=json_decode($this->curl_get($url),true);
            $access_token = $jsoninfo['access_token'];
            $car_openid = $jsoninfo['openid'];
            cookie('auth_access_token',$access_token,3600);
            cookie('car_openid',$car_openid,3600);
            // echo "2";
        }
        return $access_token;
    }

    //curl post
    public function curl_post($url,$message_text)
    {
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $message_text);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    //curl get
    public function curl_get($url)
    {
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在   
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);//返回文本流,
        $data=curl_exec($ch);
        curl_close($ch);
        return $data;
    }


    public function get_code()
    {
        $state = I('get.state');
        $redirect_url = urlencode(C('DOMAIN_ADD').U('get_userinfo'));
        $getcode_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".C('WX_APPID')."&redirect_uri=".$redirect_url."&response_type=code&scope=snsapi_userinfo&state=".$state."#wechat_redirect";
        redirect($getcode_url);
    }

    public function get_user_info($access_token , $car_openid)
    {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$car_openid."&lang=zh_CN";   
        $userinfo_json=json_decode($this->curl_get($url),true);
        return $userinfo_json;
    }


    public function get_userinfo()
    {
        if (!I('get.code')) {
            $return_url = I('get.return_url');
            $state = $return_url;
            //获取code
            $this->redirect('get_code',array('state' => $state));
        }else{
            $code = I('get.code');
            $return_url = urldecode(I('get.state'));
            //dump($return_url);

            // 获取access_token
            $access_token = $this->get_access_token($code);
            $car_openid = cookie('car_openid');

            //拉取用户信息
            $userinfo = $this->get_user_info($access_token , $car_openid);
            //headimgurl去掉http://wx.qlogo.cn/
            $userinfo['headimgurl'] = substr($userinfo['headimgurl'], 19);
            //dump($userinfo);
            /*unset($userinfo['headimgurl']); 
            //dump($userinfo);*/
            $userinfo_json = urlencode(json_encode($userinfo));
            //dump($userinfo_json);

            $this->redirect($return_url,array('userinfo_json' => $userinfo_json));


        }
        
    }

}
