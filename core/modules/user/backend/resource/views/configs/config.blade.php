<table class="table table-bordered">
    <tbody>
    <tr class="text-center">
        <th width="200">
            <label for="text" class="control-label">{!! z_language('Allow Registration') !!}</label>
        </th>
        <td>
            <div class="col-md-6 col-xs-12 text-left">
                <span><input class="float-left" type="radio" name="allow_reg" value="1"> Yes </span>
                <span><input class="float-left" type="radio" name="allow_reg" value="0"> No </span>
            </div>
        </td>
    </tr>
    <tr class="text-center">
        <th width="200">
            <label for="text" class="control-label">{!! z_language('Allow Login') !!}</label>
        </th>
        <td>
            <div class="col-md-6 col-xs-12 text-left">
                <span><input class="float-left" type="radio" name="allow_login" value="1"> Yes </span>
                <span><input class="float-left" type="radio" name="allow_login" value="0"> No </span>
            </div>
        </td>
    </tr>
    <tr class="text-center">
        <th width="200">
            <label for="text" class="control-label">{!! z_language('Enable Activation?') !!}</label>
        </th>
        <td>
            <div class="col-md-6 col-xs-12 text-left">
                <span><input class="float-left" type="radio" name="enable_active" value="1"> Yes </span>
                <span><input class="float-left" type="radio" name="enable_active" value="0"> No </span>
            </div>
        </td>
    </tr>
    <tr class="text-center">
        <th width="200">
            <label for="text" class="control-label">{!! z_language('Welcome Email?') !!}</label>
        </th>
        <td>
            <div class="col-md-6 col-xs-12 text-left">
                <span><input class="float-left" type="radio" name="welcom_email" value="1"> Yes </span>
                <span><input class="float-left" type="radio" name="welcom_email" value="0"> No </span>
            </div>
        </td>
    </tr>
    <tr class="text-center">
        <th>
            <label for="text" class="control-label">{!! z_language('Username minimum length?') !!}</label>
        </th>
        <td>
            <div class="col-md-6 col-xs-12">
                <input type="text" class="form-control" name="user_minimum_length">
            </div>
        </td>
    </tr>
    <tr class="text-center">
        <th>
            <label for="text" class="control-label">{!! z_language('Password minimum length?') !!}</label>
        </th>
        <td>
            <div class="col-md-6 col-xs-12">
                <input type="text" class="form-control" name="pwd_minimum_length">
            </div>
        </td>
    </tr>
    <tr class="text-center">
        <th>
            <label for="text" class="control-label">{!! z_language('Login attemps') !!}</label>
        </th>
        <td>
            <div class="col-md-6 col-xs-12">
                <input type="text" class="form-control" name="login_attemps">
            </div>
        </td>
    </tr>
    <tr class="text-center">
        <th>
            <label for="text" class="control-label">{!! z_language('Activation link exists in') !!}</label>
        </th>
        <td>
            <div class="col-md-6 col-xs-12">
                <input type="text" class="form-control" name="activation_time">
            </div>
        </td>
    </tr>
    </tbody>
</table>