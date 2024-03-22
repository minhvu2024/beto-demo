<table class="table table-bordered">
    <tr>
        <th width="150">
            <label for="text" class="control-label">{!! z_language('Multiple Language') !!}</label>
        </th>
        <td>
            <div class="col-md-6 col-xs-12">
                <input type="checkbox" name="{!! $keyName !!}language.multiple" value="1">
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
                <select name="{!! $keyName !!}language.default" class="form-control">
                    @foreach($language as $k=>$_language)
                        @if(!isset($config['language']['lists']) || is_array($config['language']['lists']   )&&in_array($k,$config['language']['lists']))
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
                    <input type="checkbox" name="{!! $keyName !!}language.lists"
                           value="{!! $k !!}"> &nbsp; <span
                            class="flag-icon flag-icon-{{$_language['flag']}}"></span> &nbsp;
                @endforeach
            </div>
        </td>
    </tr>
</table>