<?php

namespace App\Tool\SMS;
use App\Models\M3Result;

class SendTemplateSMS
{
    //主帐号
    private $accountSid= '8a216da856c131340156d0c4fe9e0b44';

    //主帐号Token
    private $accountToken= 'ae05519a188c400cad4e5956f3ab5735';

    //应用Id
    private $appId='8a216da856c131340156d0cf9d210b4a';

    //请求地址，格式如下，不需要写https://
    private $serverIP='sandboxapp.cloopen.com';

    //请求端口
    private $serverPort='8883';

    //REST版本号
    private $softVersion='2013-12-26';

    /**
     * 发送模板短信
     * @param to 手机号码集合,用英文逗号分开
     * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
     * @param $tempId 模板Id
     */
    function sendTemplateSMS($to,$datas,$tempId)
    {
        $m3_result = new M3Result();
        // 初始化REST SDK
        $rest = new CCPRestSDK($this->serverIP, $this->serverPort, $this->softVersion);
        $rest->setAccount($this -> accountSid, $this-> accountToken);
        $rest->setAppId($this -> appId);

        // 发送模板短信
        //echo "Sending TemplateSMS to $to <br/>";
        $result = $rest->sendTemplateSMS($to,$datas,$tempId);
        if($result == NULL ) {
            $m3_result->status = 3;
            $m3_result->message = 'result error!';
        }
        if($result->statusCode!=0) {
            $m3_result->status = $result->statusCode;
            $m3_result->message = $result->statusMsg;
            //TODO 添加错误处理逻辑
        }else{
            $m3_result->status = 0;
            $m3_result->message = '发送成功';
            //TODO 添加成功处理逻辑
        }
        return $m3_result;
    }
}

//sendTemplateSMS("18576437523", array(1234, 5), 1);
