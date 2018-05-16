<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Buzzooka') }} - login </title>
    
    <link rel="stylesheet" type="text/css" href="//cdn.materialdesignicons.com/2.0.46/css/materialdesignicons.min.css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/buzzooka.css') }}" rel="stylesheet">

</head>
<body>
    <div id="app" class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-push-4 col-md-4">
                    <div class="login">
                        <div class="login-head">
                            <div class="logo">
                                <img src="{{URL::asset('/img/logo/color-logo.png')}}" alt="Logo" width="250">
                            </div>
                            <div class="title">
                                <h1> Log In </h1>
                            </div>
                        </div>
                        <div class="login-form">
                            <form method="post" action="/login">
                                {{ csrf_field() }}
                                <div class="input-group{{ $errors->has('login') ? ' has-error' : '' }}">
                                    <span class="input-group-addon"> <img src="{{URL::asset('/img/icons/login/loginemail.png')}}"> </span>
                                    <input id="text" type="text" placeholder="Email or username" class="form-control login-field" name="login" required autofocus>
                                </div>
                                <div class="input-group{{ $errors->has('login') ? ' has-error' : '' }}">
                                    <span class="input-group-addon"> <img src="{{URL::asset('/img/icons/login/loginpass.png')}}"> </span>
                                    <input id="password" type="password" placeholder="Your password" class="form-control login-field" name="password" required>
                                </div>
                                @if ($errors->any())
                                    <span class="help-block">
                                        <strong>{{ $errors->first() }}</strong>
                                    </span>
                                @endif
                                <div class="divider"></div>
                                <div class="form-group login-option">
                                    <div class="checkbox pull-left">
                                        <label>
                                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}> 
                                            Remember Me 
                                        </label>
                                    </div>
                                    <div class="forgot pull-right">
                                        <a class="" href="{{ route('password.request') }}">
                                            Forgot Your Password?
                                        </a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-control btn btn-login">
                                        Log in
                                    </button>
                                </div>
                                <div class="signup">
                                    <label>Not a member yet? <a href="#"> Sign up </a></label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>