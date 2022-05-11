<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{config('admin.title')}} | {{ trans('admin.login') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css") }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/font-awesome/css/font-awesome.min.css") }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ admin_asset("vendor/laravel-admin/AdminLTE/dist/css/AdminLTE.min.css") }}">
    <script src="https://sf3-cn.feishucdn.com/obj/static/lark/passport/qrcode/LarkSSOSDKWebQRCode-1.0.1.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition login-page" style="height: 100vh; display: flex; align-items: center; background: url({{config('admin.login_background_image') ?: '/vendor/laravel-admin-oauth/img/background.jpg'}}) no-repeat;background-size: cover;">

    @if (config('admin-oauth.allowed_password_login'))

        <div class="login-box" id="login-box-password" style="opacity: 0.8;">

            <div class="login-logo">
                <b style="color:white; font-size: 20px; ">{{config('admin.name')}}</b>
            </div>

            <div class="login-box-body" style="box-shadow: 0px 20px 80px 0px rgb(0 0 0 / 30%);">
                <p class="login-box-msg">{{ trans('admin.login') }}</p>

                <form action="{{ admin_base_path('auth/login') }}" method="post">
                    <div class="form-group has-feedback {!! !$errors->has('username') ?: 'has-error' !!}">

                        @if($errors->has('username'))
                            @foreach($errors->get('username') as $message)
                                <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$message}}</label><br>
                            @endforeach
                        @endif

                        <input type="text" class="form-control" placeholder="{{ trans('admin.username') }}" name="username" value="{{ old('username') }}">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback {!! !$errors->has('password') ?: 'has-error' !!}">

                        @if($errors->has('password'))
                            @foreach($errors->get('password') as $message)
                                <label class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$message}}</label><br>
                            @endforeach
                        @endif

                        <input type="password" class="form-control" placeholder="{{ trans('admin.password') }}" name="password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-4"></div>
                        <div class="col-xs-4">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('admin.login') }}</button>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 20px; text-align: center;">
                        第三方账号登录
                    </div>
                    <div class="row" style="margin-top: 20px; text-align: center;">
                        @foreach($sources as $source => $sourceName)
                        <div class="col-md-2">
                            <a href="{{ admin_url('/oauth/authorize?source=' . $source) }}" style="">
                                <img src="{{ url('/vendor/laravel-admin-oauth/img/' . $source . '.png') }}" title="{{ $sourceName }}">
                            </a>
                        </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>

        <script type="text/javascript">

        </script>
    @endif
</body>
</html>
