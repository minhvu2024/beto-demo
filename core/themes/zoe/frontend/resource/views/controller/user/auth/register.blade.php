@section("content")
    <div class="section section-breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Register</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-sm-5">
                    <div class="basic-login">
                        <form role="form" method="post" action="{!! route('frontend:guest:register:post') !!}">
                            <div class="_csrf_input">@csrf</div>
                            <div class="form-group">
                                <label for="register-username"><i class="icon-user"></i>
                                    <b>{!! z_language('UserName') !!} </b></label>
                                <input class="form-control" id="register-username" type="text" placeholder=""
                                       name="username">
                            </div>
                            <div class="form-group">
                                <label for="register-password"><i class="icon-lock"></i>
                                    <b>{!! z_language('Password') !!} </b></label>
                                <input class="form-control" id="register-password" type="password" placeholder=""
                                       name="password">
                            </div>
                            <div class="form-group">
                                <label for="register-password2"><i class="icon-lock"></i>
                                    <b>{!! z_language('Re-enter Password') !!}</b></label>
                                <input class="form-control" id="register-password2" type="password" placeholder=""
                                       name="paswword2">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn pull-right">{!! z_language('Register') !!}</button>
                                <div class="clearfix"></div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-6 col-sm-offset-1 social-login">
                    <p>You can use your Facebook or Twitter for registration</p>
                    <div class="social-login-buttons">
                        <a href="#" class="btn-facebook-login">Use Facebook</a>
                        <a href="#" class="btn-twitter-login">Use Twitter</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection