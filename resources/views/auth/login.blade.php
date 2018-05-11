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

</head>
<body id="app" class="overlay">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-push-4 col-md-4">
                <div class="login">
                    <div class="login-head">
                        <img src="{{URL::asset('/img/logo/color-logo.png')}}" alt="profile Pic" width="200">
                        <h2> Log In </h2>
                    </div>
                    <div class="login-form">
                        <form method="post" action="/login">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('login') ? ' has-error' : '' }}">
                                <input id="text" type="text" placeholder="Email or username" class="form-control" name="login" required autofocus>
                            </div>
                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <input id="password" type="password" placeholder="Your password" class="form-control" name="password" required>
                            </div>
                            @if ($errors->any())
                                <span class="help-block">
                                    <strong>{{ $errors->first() }}</strong>
                                </span>
                            @endif
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                                <div>
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        Forgot Your Password?
                                    </a>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary">
                                    Login
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>