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
              <span class="lbl">Already have an account?</span> <a href="{{route('login')}}" class="button button-rounded button-reveal button-large button-purple"><i class="icon-lock3"></i><span>Login</span></a>
            </div>  

            <div class="card-body">

                <form id="register-form" name="register-form" class="nobottommargin" method="POST" action="{{ route('password.email') }}" aria-label="{{ __('Reset Password') }}">

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @csrf

                    <div class="col_full">
                            <h3>Forgot your password?</h3>
                            <p>Enter your email address below and we'll get you back on track.</p>
                    </div>



                    <div class="col_full">
                        <label for="register-form-email">Email Address:</label>

                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                        
                    </div>

                    <div class="col_full nobottommargin">
                        <button type="submit" class="button button-3d button-black nomargin" id="register-form-submit" name="register-form-submit" value="register">Send Password Reset Link</button>
                    </div>

            </form>

            </div>

        </div>

    </div>

</div>

@endsection
