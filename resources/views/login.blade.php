@extends('master')
@section('title', '登录')
@section('content')
    <div class="weui_cells_title"></div>
    <div class="weui_cells weui_cells_form">
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">帐号</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="tel" name="username" placeholder="邮箱或手机号"/>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">密码</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="tel" name="password" placeholder="不少于6位"/>
            </div>
        </div>
        <div class="weui_cell weui_vcode">
            <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="text" name="validate_code" placeholder="请输入验证码"/>
            </div>
            <div class="weui_cell_ft">
                <img src="/service/validate_code/create" class="bk_validate_code"/>
            </div>
        </div>
    </div>
    <div class="weui_cells_tips"></div>
    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_primary" href="javascript:" onclick="onLoginClick();">登录</a>
    </div>
    <a href="/register" class="bk_bottom_tips bk_important">没有帐号? 去注册</a>
@endsection
@section('my-js')
    <script type="text/javascript">
        $('.bk_validate_code').click(function () {
            $(this).attr('src', '/service/validate_code/create?random='+ Math.random());
        });
    </script>
    <script type="text/javascript">
        function onLoginClick()
        {
            //账号
            var username = $('input[name=username]').val();
            console.log(username.length == 0);
            if (username.length === 0) {
                $('.bk_toptips').show();
                $('.bk_toptips span').html('账号不能为空');
                setInterval(function () {
                    $('.bk_toptips').hide();
                }, 5000);
                return;
            }
            //判断账号格式
            if (username.indexOf('@') == -1) {
                if (username.length != 11 || username[0] != 1) {
                    $('.bk_toptips').show();
                    $('.bk_toptips span').html('账号格式不正确');
                    setInterval(function () {
                        $('.bk_toptips').hide();
                    }, 5000);
                    return;
                }
            }else {
                if (username.indexOf('.') == -1) {
                    $('.bk_toptips').show();
                    $('.bk_toptips span').html('账号格式不正确');
                    setInterval(function () {
                        $('.bk_toptips').hide();
                    }, 5000);
                    return;
                }
            }
            //判断密码
            var password = $('input[name=password]').val();
            if (password.length === 0) {
                $('.bk_toptips').show();
                $('.bk_toptips span').html('密码不能为空');
                setInterval(function () {
                    $('.bk_toptips').hide();
                }, 5000);
                return;
            }
            if (password.length < 6) {
                $('.bk_toptips').show();
                $('.bk_toptips span').html('密码不能小于6位');
                setInterval(function () {
                    $('.bk_toptips').hide();
                }, 5000);
                return;
            }

            //验证码
            var validate_code = $('input[name=validate_code]').val();
            if (validate_code.length === 0) {
                $('.bk_toptips').show();
                $('.bk_toptips span').html('验证码不能为空');
                setInterval(function () {
                    $('.bk_toptips').hide();
                }, 5000);
                return;
            }
            if (validate_code.length < 4) {
                $('.bk_toptips').show();
                $('.bk_toptips span').html('验证码不能少于4位');
                setInterval(function () {
                    $('.bk_toptips').hide();
                }, 5000);
                return;
            }
            //ajax登录
            $.ajax({
                type: 'POST',
                url:'/service/login',
                dateType:'json',
                cache:false,
                data:{username:username,password:password,validate_code:validate_code,_token:"{{csrf_token()}}"},
                success:function (data){
                    data = JSON.parse(data);
                    if (data === null) {
                        $('.bk_toptips').show();
                        $('.bk_toptips span').html('服务器错误');
                        setInterval(function () {
                            $('.bk_toptips').hide();
                        }, 5000);
                    }
                    if (data.status !== 0) {
                        $('.bk_toptips').show();
                        $('.bk_toptips span').html(data.message);
                        setInterval(function () {
                            $('.bk_toptips').hide();
                        }, 5000);
                    }
                    if (data.status === 0) {
                        $('.bk_toptips').show();
                        console.log( $('.bk_toptips span'));
                        $('.bk_toptips span').html('登录成功');
                        setInterval(function () {
                            $('.bk_toptips').hide();
                        }, 5000);
                    }
                    location.href="/category";
                }
            });
        }
    </script>
@endsection