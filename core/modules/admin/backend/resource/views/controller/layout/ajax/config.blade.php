@section('content')
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a name="config" data-toggle="tab" href="#config">Config</a></li>
            @php
                $dataConfigAll = [];

            @endphp
            @foreach($views as $key=>$view)
                @continue(!isset($view['view']))
                @php

                    $dataConfigAll[$key] = isset($view['data'])?$view['data']:[];
                @endphp
                <li><a data-toggle="tab" data-name="{!! $key !!}" href="#{{md5($view['view'])}}">{{$view['label']}}</a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content">
            <div id="config" class="tab-pane fade in active">
                <div class="row">
                    <div class="col-md-6"> <table class="table table-bordered">
                            <tbody>
                            <tr class="text-center">
                                <td width="150"><label for="text">Title</label></td>
                                <td><input type="text" name="opt.title" class="form-control" id="title" placeholder="Tên"></td>
                            </tr>
                            @if(count($func)>0)
                                <tr>
                                    <td class="text-center"><label for="text">Action</label></td>
                                    <td>
                                        <select name="cfg.func" class="form-control">
                                            @foreach($func as $_func=>$_view)
                                                <option data-view="{!! $_view !!}"
                                                        value="{!! $_func !!}">{!! $_func !!}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            @endif
                            @if(count($list_views)>0)
                                <tr>
                                    <td class="text-center"><label for="text">View</label></td>
                                    <td>

                                        <select name="cfg.view" class="form-control">
                                            @foreach($list_views as $k=>$_view)
                                                <option value="{!! $_view["view"] !!}">{{$_view["label"]}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td class="text-center"><label for="text">Stats</label></td>
                                <td>
                                    <input type="radio" name="cfg.status" value="1"> <i>Yes</i>&nbsp;
                                    <input type="radio" name="cfg.status" value="0"> <i>No</i> &nbsp;
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center"><label for="text">Load View</label></td>
                                <td>
                                    <input type="radio" name="cfg.loadview" value="include"> <i> Include </i>
                                    &nbsp;
                                    <input type="radio" name="cfg.loadview" value="copy"> <i> Copy </i>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center"><label for="text">Public</label></td>
                                <td>
                                    <input data-target=".wrap-dynamic" type="radio" name="cfg.public" value="1"> <i>Database</i>
                                    &nbsp;
                                    <input data-target=".wrap-dynamic" type="radio" name="cfg.public" value="0"> <i>Local</i>
                                    &nbsp;
                                </td>
                            </tr>
                            <tr class="wrap-dynamic"
                                style="{{isset($items["cfg"]['public']) && $items["cfg"]['public']==1 ?"display:table-row;":"display:none;"}}">
                                <td class="text-center"><label for="text">Dynamic</label></td>
                                <td>
                                    <input type="radio" name="cfg.dynamic" value="1"> Yes
                                    <input type="radio" name="cfg.dynamic" value="0"> No
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center"><label for="text">Render</label></td>
                                <td>
                                    @php
                                        $currentRender = isset($items["cfg"]['render'])?$items["cfg"]['render']:"blade";
                                    @endphp
                                    <input data-target=".wrap-image_base64" {!! $currentRender =="php" ?"checked":"" !!} type="radio"
                                           name="cfg.render"
                                           value="php"> Php
                                    <input data-target=".wrap-image_base64" {!! $currentRender =="blade" ?"checked":"" !!} type="radio"
                                           name="cfg.render" value="blade"> Blade
                                </td>
                            </tr>

                            <tr  style="{{isset($items["cfg"]['render']) && $items["cfg"]['render']=="php" ?"display:table-row;":"display:none;"}}">
                                <td class="text-center"><label for="text">Refresh</label></td>
                                <td>
                                    <input  type="radio" name="cfg.config.refresh" value="1"> <i>Yes</i>
                                    &nbsp;
                                    <input  type="radio" name="cfg.config.refresh" value="0"> <i>No</i>
                                    &nbsp;
                                </td>
                            </tr>


                            @if(isset($tags[0]))
                                <tr>
                                    <td class="text-center"><label for="text">Tag</label></td>
                                    <td>
                                        <input type="radio" name="cfg.tag" value="none"> None
                                        <input type="radio" name="cfg.tag" value="block"> Tag
                                        @foreach($tags as $tag)
                                            <input type="radio" name="cfg.tag" value="{!! $tag !!}"> {!! $tag !!}
                                        @endforeach
                                    </td>
                                </tr>
                            @endif

                            </tbody>
                        </table></div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <td class="text-center"><label for="text">Compiler</label></td>
                            </tr>
                            <tr>

                                <td>
                                    <select id='optgroup' multiple='multiple'>
                                        @php
                                            $i = 0;

                                        @endphp
                                        @foreach($compiler as $_label=>$_hook)
                                            <optgroup data-name="{{$_label}}" label='{{$_label}}'>
                                                @foreach($_hook as $val)
                                                    @php
                                                        $order = 0;
                                                    @endphp
                                                    @if(isset($items["cfg"]["compiler"][$_label]) && in_array($val,$items["cfg"]["compiler"][$_label]))
                                                        @php $order = array_search($val,$items["cfg"]["compiler"][$_label])+1; @endphp
                                                        <option data-group="{{$_label}}-{{$val}}" data-order="{{$order}}"
                                                                data-index="{{$i++}}" selected
                                                                value='{{$_label}}-{{$val}}'>{{$val}}</option>
                                                    @else
                                                        <option data-group="{{$_label}}-{{$val}}" data-order="{{$order}}"
                                                                data-index="{{$i++}}"
                                                                value='{{$_label}}-{{$val}}'>{{$val}}</option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                        @endforeach

                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
            @foreach($views as $view)
                @continue(!isset($view['view']))
                <div id="{{md5($view['view'])}}" class="tab-pane fade">
                    @include($view['view'],["config"=>$items,"data"=>$view['data'],"all"=>$dataConfigAll,'list_views'=>$list_views])
                </div>
            @endforeach
        </div>
        <div style="display: none"><textarea id="data_config"></textarea></div>
        <script>
            $('input[type=radio][name="cfg.public"]','input[type=radio][name="cfg.render"]').change(function () {
                if (this.value === "1") {
                    $($(this).attr('data-target')).show();
                } else {
                    $($(this).attr('data-target')).hide();
                }
            });
        </script>
        <style>
            .ms-container{
                width: 100%;
            }
        </style>
    </div>
@endsection