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
<body>
    <div id="app" class="overlay">
        <div id="side-image" class="image-fader">
            <div id="side-image" class="image-fader"></div>
        </div>
        <div class="container-fluid">
            <div class="row" style="margin-top: 24px !important">
                <div class="col-xs-6 hidden-lg hidden-xl">
                    <a href="/">
                        <span class="logo-lg">
                            <img src="{{ asset('img/logo@2x.png') }}" width="120">
                        </span>
                    </a>
                </div>
                <div class="col-xs-6 col-lg-12">
                    <a href="/" class="close" type="button" aria-label="Close">
                        <span class="mdi mdi-close" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
            <div class="row" style="margin-top: 42px; margin-bottom: 40px !important">
                <div class="col-lg-push-5 col-lg-7">
                    <div class="col-md-push-3 col-md-6 col-md-pull-2 col-xl-push-3 col-xl-6 col-xl-pull-3">
                        <div class="text-center">
                            <div class="row">
                                <div class="logo logo-auth-page col-centered hidden-xs hidden-sm hidden-md"></div>
                            </div>
                        </div>
                        <div class="content-margin-top">
                            <div class="text-center">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h2>Your Back Great!</h2>
                                    </div>
                                    @if ($errors->any())
                                        <span class="help-block">
                                            <strong>{{ $errors->first() }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <!-- alert message -->
                                    </div>
                                </div>
                                <form method="post" action="/login">
                                    {{ csrf_field() }}
                                   <div class="form-group{{ $errors->has('login') ? ' has-error' : '' }}">
                                        <div class="col-xs-12">
                                            <input id="text" type="text" placeholder="Email or username" class="form-control" name="login" required autofocus>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <div class="col-xs-12">
                                            <input id="password" type="password" placeholder="Your password" class="form-control" name="password" required>
                                        </div>
                                    </div>
                                     <div style="text-align: left">
                                        <div class="col-md-12">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <br><br>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                           <button type="submit" class="form-control btn btn-primary">
                                                Login
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                Forgot Your Password?
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
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