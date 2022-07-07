<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>News</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="{{asset('dist/css/login.css')}}" rel="stylesheet">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
    <div class="login-page">
        <div class="form">
            @if ($message = Session::get('error') )
            <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
            @endif
            @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
                </ul>
                </div>
            @endif
            <form class="login-form" method="post" action="{{ route('checkLogin') }}">
                @csrf
                <input type="email" placeholder="Email" name="email"/>
                <input type="password" style="margin-bottom:0;" placeholder="Mật khẩu"  name="password"/>
                <a href="#" class="message"  style="float:right">Forgot Password?</a>
                <input class="submit" type="submit" name="login" value="Đăng nhập" >
                {{-- <p class="message centered"  >Not a member? <a  href="{{ route('register') }}">Sign Up</a></p> --}}
                <p class="message centered"  ><a  href="{{ route('home') }}">Go to home page </a></p>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js?fbclid=IwAR3Bk9QbxEg0w7GQWUS76e_StOdgxU0joSzHiENkwk4JX2nRW5UTs6uNSV0" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"crossorigin="anonymous"></script>
    <script>
        $('.message a').click(function(){
              $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
        });
    </script>

    </body>
</html>
