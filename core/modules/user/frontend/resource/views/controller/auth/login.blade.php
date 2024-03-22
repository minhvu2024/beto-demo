@section("content")
    <div class="section section-breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>{!! z_language('Login') !!}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="container">

            <div class="row">
                <div class="col-sm-5">
                    <div class="basic-login">
                        <form role="form" method="post" action="{!! route('frontend:guest:login:post') !!}">
                            <div class="_csrf_input">@csrf</div>
                            <div class="form-group">
                                <label for="login-username"><i class="icon-user"></i> <b>Username or Email</b></label>
                                <input class="form-control" id="login-username" type="text" placeholder=""
                                       name="username">

                            </div>
                            @error("username")
                            <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <div class="form-group">
                                <label for="login-password"><i class="icon-lock"></i> <b>Password</b></label>
                                <input class="form-control" id="login-password" type="password" placeholder=""
                                       name="password">
                            </div>
                            <div class="form-group">
                                <label class="checkbox">
                                    <input type="checkbox"> Remember me
                                </label>
                                <a href="page-password-reset.html" class="forgot-password">Forgot password?</a>
                                <button type="submit" class="btn pull-right">Login</button>
                                <div class="clearfix"></div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-7 social-login">
                    <p>{!! z_language('Or login with your Facebook or Twitter') !!}</p>
                    <div class="social-login-buttons">
                        <a href="#" class="btn-facebook-login">Login with Facebook</a>
                        <a href="#" class="btn-twitter-login">Login with Twitter</a>
                    </div>
                    <div class="clearfix"></div>
                    @if(route::has('register'))
                        <div class="not-member">
                            <p>Not a member? <a href="{!! route('register') !!}">Register here</a></p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection