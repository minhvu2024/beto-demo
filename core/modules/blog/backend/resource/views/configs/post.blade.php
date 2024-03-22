<table class="table table-bordered">
    <tbody>
    <tr>
        <th width="150">
            <label for="text" class="control-label">{!! z_language('Multiple Language') !!}</label>
        </th>
        <td>
            <div class="col-md-6 col-xs-12">
                <input type="checkbox" name="post.language.multiple" value="1">
            </div>
        </td>
    </tr>
    <tr>
        <th width="150">
            <label for="text" class="control-label">{!! z_language('Language Default') !!}</label>
        </th>
        <td>
            <div class="col-md-6 col-xs-12">
                @php
                    $language = config('zoe.language');
                @endphp
                <select name="post.language.default" class="form-control">
                    @foreach($language as $k=>$_language)
                        @if(!isset($config['post']['language']['lists']) || in_array($k,$config['post']['language']['lists']))
                            <option value="{!! $_language['lang'] !!}">{!! $_language['label'] !!}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <th width="150">
            <label for="text" class="control-label">{!! z_language('Language List') !!}</label>
        </th>
        <td>
            <div class="col-md-6 col-xs-12">
                @php
                    $language = config('zoe.language');
                @endphp
                @foreach($language as $k=>$_language)
                    <input type="checkbox" name="post.language.lists"
                           value="{!! $_language['lang'] !!}"> &nbsp; <span
                            class="flag-icon flag-icon-{{$_language['flag']}}"></span> &nbsp;
                @endforeach
            </div>
        </td>
    </tr>
    <tr class="text-center">
        <th width="150">
            <label for="text" class="control-label">{!! z_language('Image width') !!}</label>
        </th>
        <td>
            <div class="col-md-6 col-xs-12">
                <input type="text" class="form-control" name="post.image.width">
            </div>
        </td>
    </tr>
    <tr class="text-center">
        <th width="150">
            <label for="text" class="control-label">{!! z_language('Image height') !!}</label>
        </th>
        <td>
            <div class="col-md-6 col-xs-12">
                <input type="text" class="form-control" name="post.image.height">
            </div>
        </td>
    </tr>
    <tr class="text-center">
        <th width="150">
            <label for="text" class="control-label">{!! z_language('Image unit') !!}</label>
        </th>
        <td>
            <div class="col-md-6 col-xs-12">
                <select class="form-control" name="post.image.unit">
                    <option value="px">px</option>
                    <option value="px">em</option>
                </select>
            </div>
        </td>
    </tr>
    <tr class="text-center">
        <th width="150">
            <label for="text" class="control-label">{!! z_language('Items per page') !!}</label>
        </th>
        <td>
            <div class="col-md-6 col-xs-12">
                <input type="text" class="form-control" name="post.items_per_page">
            </div>
        </td>
    </tr>
    </tbody>
</table>
@push('links')
    <link rel="stylesheet" href="{{asset("module/admin/assets/flag/css/flag-icon.min.css")}}">
@endpush
