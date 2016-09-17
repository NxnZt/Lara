<?php

namespace App\Http\Controllers\Service;
use App\Entity\Member;
use App\Entity\TempEmail;
use Illuminate\Http\Request;
use App\Tool\Validate\ValidateCode;
use App\Tool\SMS\SendTemplateSMS;
use App\Entity\TempPhone;
use App\Models\M3Result;
use App\Http\Controllers\Controller;

class ValidateController extends Controller
{
   //随机因子
   private $charset = '1234567890';
   public function create(Request $request)
   {
       $validateCode = new ValidateCode();
       $request->session()->put('validate_code', $validateCode->getCode());
       return $validateCode->doimg();
   }
   public function sendSMS(Request $request)
   {
       $m3_result = new M3Result();
       $phone = $request->input('phone', '');
       if ($phone == '') {
           $m3_result -> status = 1;
           $m3_result -> message = '手机号不能为空';
           return $m3_result -> toJson();
       }
       $code = '';
       //生成随机码
       $_len = strlen($this->charset) - 1;
       for ($i = 0;$i < 6;++$i) {
           $code .= $this->charset[mt_rand(0, $_len)];
       }
       $sendTemplateSMS = new SendTemplateSMS();
       $m3_result = $sendTemplateSMS -> sendTemplateSMS($phone, array($code, 60), 1);
       if ($m3_result -> status == 0){
           $tempPhone = new TempPhone();
           $tempPhone -> phone = $phone;
           $tempPhone -> code = $code;
           $tempPhone -> deadtime = date('Y-m-d H:i:s', time() + 60 * 60);
           $tempPhone -> save();
       }
       return $m3_result -> toJson();
   }
   public function validateEmail(Request $request)
   {
       $member_id = $request->input('member_id', '');
       $code = $request->input('code', '');

       $tempEmail = TempEmail::where('member_id', $member_id)->first();
       //判断$tempEmail是否为空
       if ($tempEmail == null) {
           return '验证异常';
       }

       //比较code是否相等
       if ($tempEmail->code == $code) {
          //判断是否过期
          if (time() > strtotime($tempEmail->deadtime)) {
              return '该链接已失效';
          }
          //修改用户的状态
          $member = Member::find($member_id);
          $member->active = 1;
          $member->save();
          return view('/login');
       } else {
           return '该链接已失效';
       }
   }
}
