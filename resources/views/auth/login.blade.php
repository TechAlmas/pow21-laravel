@extends('layouts.app')

@section('content')

<div class="content-wrap nopadding">

    <div class="section nobg full-screen nopadding nomargin">

        <div class="col_one_third nopadding nomargin login-left">

            <div class="center">
                <a href="#"><img src="{{ asset('images/logo-resto-dark.png')}}" alt="Canvas Logo"></a>
            </div>            
            <h4>Cannabis Data Provider</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Unde, vel odio non dicta provident sint ex autem mollitia dolorem illum repellat ipsum aliquid illo similique sapiente fugiat minus ratione.</p>

        </div>

        <div class="col_two_third col_last nopadding nomargin login-right">          

            <div class="login-top-box">
              <span class="lbl">Don't Have Account?</span> <a href="{{route('register')}}" class="button button-rounded button-reveal button-large button-red tright"><i class="icon-angle-right"></i><span>SignUp</span></a>
            </div>  

            <div class="card-body">

                <form id="login-form" name="login-form" class="nobottommargin" method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">

                    @csrf
                    <div class="col_full">
                        <h3>Login to your Account</h3>

                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Unde, vel odio non dicta provident sint ex autem mollitia dolorem illum repellat ipsum aliquid illo similique sapiente fugiat minus ratione.</p>
                    </div>

                    <div class="col_full">
                        <label for="login-form-username">Email Address:</label>
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="col_full">
                        <label for="login-form-password">Password:</label>
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="col_full nobottommargin">
                        <button type="submit" class="button button-3d nomargin" id="login-form-submit" name="login-form-submit" value="login">Login</button>
                        <a href="{{ route('password.request') }}" class="fright">Forgot Password?</a>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
