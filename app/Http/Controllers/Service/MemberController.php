<?php

namespace App\Http\Controllers\Service;

use App\Tool\Validate\ValidateCode;
use App\Http\Controllers\Controller;
use App\Entity\TempPhone;
use App\Entity\TempEmail;
use App\Entity\Member;
use Illuminate\Http\Request;
use App\Models\M3Result;
use App\Models\M3Email;
use App\Tool\UUID;
use Mail;

class MemberController extends Controller
{
    public function register(Request $request)
    {
        $email = $request->input('email');
        $phone = $request->input('phone');
        $password = $request->input('password');
        $confirm = $request->input('confirm');
        $phone_code = $request->input('phone_code');
        $validate_code = $request->input('validate_code');
        //return $request->all();
        $m3_result = new M3Result();
        //数据验证
        if($email == '' && $phone == '') {
            $m3_result->status = 1;
            $m3_result->message = '手机号或邮箱不能为空';
            return $m3_result->toJson();
        }
        if($password == '' || strlen($password) < 6) {
            $m3_result->status = 2;
            $m3_result->message = '密码不少于6位';
            return $m3_result->toJson();
        }
        if($confirm == '' || strlen($confirm) < 6) {
            $m3_result->status = 3;
            $m3_result->message = '确认密码不少于6位';
            return $m3_result->toJson();
        }
        if($password != $confirm) {
            $m3_result->status = 4;
            $m3_result->message = '两次密码不相同';
            return $m3_result->toJson();
        }
        if ($phone != '') {
            //验证手机验证码
            if($phone_code == '' || strlen($phone_code) != 6) {
                $m3_result->status = 5;
                $m3_result->message = '手机验证码为6位';
                return $m3_result->toJson();
            }
            //根据手机号码得到验证码
            $tempPhone = TempPhone :: where('phone', $phone)->first();
            //判断验证码是否一致
            if ($tempPhone->code == $phone_code) {
                //判断是否过期
                if (time() > strtotime($tempPhone->deadtime)) {
                    $m3_result->status = 8;
                    $m3_result->message = '验证码失效';
                    return $m3_result->toJson();
                }
                //将数据插入到用户表中
                $member = new Member();
                $member->phone = $phone;
                $member->password = md5('pk' + $password);
                $member->save();
            } else {
                $m3_result->status = 7;
                $m3_result->message = '手机验证码不正确';
                return $m3_result->toJson();
            }
        } else {
            //邮箱验证
            if($validate_code == '' || strlen($validate_code) != 4) {
                $m3_result->status = 6;
                $m3_result->message = '验证码为4位';
                return $m3_result->toJson();
            }
            $validata_code_session = $request->session()->get('validate_code', '');
            if ($validate_code != $validata_code_session) {
                $m3_result->status = 9;
                $m3_result->message = '验证码不正确';
                return $m3_result->toJson();
            }
            //将数据插入到用户表中
            $member = new Member();
            $member->email = $email;
            $member->password = md5('pk' + $password);
            $member->save();

            //生成一个字符串
            $uuid = UUID::create();
            //实例化邮箱的实体类
            $m3_email = new M3Email();
            $m3_email->to = $email;
            $m3_email->cc = '342394749@qq.com';
            $m3_email->subject = '凯恩书店验证';
            $m3_email->content = '请于24小时点击该链接完成验证。http://www.my_laravel.com/service/validate_email'.'?member_id='.$member->id.'&code='.$uuid;

            $tempEmail = new TempEmail();
            $tempEmail->member_id = $member->id;
            $tempEmail->code = $uuid;
            $tempEmail->deadtime =  date('Y-m-d H:i:s', time() + 24 * 60 * 60);;
            $tempEmail->save();

            Mail::send('email_register', ['m3_email' => $m3_email], function ($m) use ($m3_email) {
                //$m->from('hello@app.com', 'Your Application');
                $m->to( $m3_email->to, '尊敬的用户')->cc($m3_email->cc)->subject($m3_email->subject);
            });

            $m3_result->status = 0;
            $m3_result->message = '注册成功';
            return $m3_result->toJson();
        }
    }

    public function login(Request $request)
    {
        //获取用户的信息
        $username = $request->get('username', '');
        $password = $request->get('password', '');
        $validate_code = $request->get('validate_code', '');

        $m3_result = new M3Result();

        //判断验证码是否正确
        $validate_code_sessioin = $request->session()->get('validate_code');
        if ($validate_code != $validate_code_sessioin) {
            $m3_result->status = 1;
            $m3_result->message = '验证码不正确';
            return $m3_result->toJson();
        }
        //根据用户名来判断
        if (strpos($username, '@') == true) {
            //查询数据库
            $member = Member::where('email', $username)->first();
        } else {
            $member = Member::where('phone', $username)->first();
        }
        //判断用户是否存在
        if ($member == null) {
            $m3_result->status = 2;
            $m3_result->message = '该用户不存在';
            return $m3_result->toJson();
        } else {
            //判断密码
            if (md5('bk' + $password) != $member->password) {
                $m3_result->status = 3;
                $m3_result->message = '密码不正确';
                return $m3_result->toJson();
            }
        }
        //保存用户到session中
        $request->session()->put('member', $member);
        $m3_result->status = 0;
        $m3_result->message = '登录成功';
        return $m3_result->toJson();
    }
}
