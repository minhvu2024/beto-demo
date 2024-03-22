@section('content-header')
    <h1>
        {!! @z_language(["Manager Layout"]) !!}
        <button onclick="SaveLayout(this)" url="{{route('backend:layout:ajax')}}" id="saveLayout" type="button"
                class="btn btn-default btn-md"> {!! @z_language(["Save"]) !!} </button>

    </h1>
@endsection

@section('content')

    @breadcrumb()@endbreadcrumb

    @function(view_item ($name,$middlewares,$uri,$layouts,$listsRolePremission,$datas,$status,$controllers,$controller))
    <li>
        <i class="fa fa-gears @if($status) bg-blue @else @endif"></i>

        <div class="timeline-item routers">
            <span class="time">

            </span>

            <h3 class="timeline-header"><a href="#">{!! $name !!}</a>
            </h3>
            <div class="timeline-body">
                @php
                    $url = $uri;
                    if(isset($datas['data'][$name]['uri'])){
                        $url = $datas['data'][$name]['uri'];
                    }
                    $_controller = $controller;
                    if(isset($datas['data'][$name]['controller'])){
                        $_controller = $datas['data'][$name]['controller'];
                    }

                @endphp
                <input type="hidden" name="data[{!! $name !!}].data.name" value="{!! $name !!}">
                <input type="hidden" name="data[{!! $name !!}].data.uri" value="{!! $uri !!}">
                <input type="hidden" name="data[{!! $name !!}].data.controller" value="{!! $controller !!}">

                <div class="row router">
                    <div class="col-md-1"><span class="label label-default">uri</span></div>
                    <div class="col-md-11">

                        <strong class="view">{!! asset('/') !!}
                            <span
                                    data-uri="{!! $uri !!}">{!! $url !!}</span></strong>
                        <span class="input" style="display: none">
                            <input type="text"
                                   @if($url == $uri) data-name="data[{!! $name !!}].uri"
                                   @else name="data[{!! $name !!}].uri" @endif
                                   value="{!! $url !!}"></span>
                        &nbsp;
                        <button type="button" onclick="changeUri(this)">edit</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-1"><span class="label label-default"> middleware </span>
                    </div>
                    <div class="col-md-11">
                        @php
                            $string = "";

                            if(isset($datas['data'][$name]['cache'])){
                                $cache = 'cache.response:' . $name  . "," . $datas['data'][$name]['cache'];
                                if($datas['data'][$name]['cache']>0){
                                    if(!in_array($cache,$middlewares)){
                                        $string ='&bkarow; [<strong>'.$cache.'</strong>]';
                                    }
                                }else{
                                    foreach ($middlewares as $k=>$middleware){
                                        if( \Illuminate\Support\Str::startsWith($middleware,'cache.response:')){
                                            unset($middlewares[$k]);
                                        }
                                    }
                                }
                            }
                        @endphp
                        [<strong> @php
                                echo join("</strong>] &bkarow; [<strong>",$middlewares)
                            @endphp </strong>]
                        <input type="hidden" name="data[{!! $name !!}].data.middleware"
                               value='{!! json_encode($middlewares) !!}'>
                    </div>
                </div>
                <table class="table table-borderless" style="display: table;margin-bottom: 0">

                    <tr>
                        <td style="width: 10%">
                            <label for="id_title" class="title">Layout</label>
                        </td>
                        <td>

                            <select name="data[{!! $name !!}].layout"
                                    class="form-control">
                                @foreach($layouts as $layout)
                                    @if( isset($datas['data'][$name]['layout']) && $datas['data'][$name]['layout'] ==$layout['slug'] )
                                        <option selected
                                                value="{!! $layout['slug'] !!}">{!! $layout['name'] !!}</option>
                                    @else
                                        <option value="{!! $layout['slug'] !!}">{!! $layout['name'] !!}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 10%">
                            <label for="id_title" class="title">Controller</label>
                        </td>
                        <td>
                            <select name="data[{!! $name !!}].controller"
                                    class="form-control">
                                @foreach($controllers as $controller=>$namespace)
                                    @if( $controller == $_controller )
                                        <option selected
                                                value="{!! $controller !!}">{!! $controller !!}</option>
                                    @else
                                        <option value="{!! $controller !!}">{!!$controller !!}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="id_title" class="title">Cache</label>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control"
                                       name="data[{!! $name !!}].cache"
                                       value="{!! isset($datas['data'][$name]['cache'])?$datas['data'][$name]['cache']:"" !!}">
                                <span class="input-group-addon"><strong>s</strong></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="id_title" class="title">Acl</label>
                        </td>
                        <td>
                            <select name="data[{!! $name !!}].acl"
                                    class="form-control">
                                <option value="login">Login</option>

                                @foreach($listsRolePremission as $premissions)
                                    @foreach($premissions as $premission)
                                        <option @if(isset($datas['data'][$name]['acl']) && $datas['data'][$name]['acl'] == $premission->name) selected
                                                @endif value="{!! $premission->name !!}">{!! $premission->name !!}</option>
                                    @endforeach
                                @endforeach
                                <option @if(isset($datas['data'][$name]['acl']) && $datas['data'][$name]['acl'] == "no-login") selected
                                        @endif value="no-login">No Login
                                </option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label for="id_title" class="title">Status</label>
                        </td>
                        <td>

                            <div>
                                <input @if(isset($datas['data'][$name]['status']) && $datas['data'][$name]['status'] == "1") checked
                                       @endif type="radio" name="data[{!! $name !!}].status"
                                       value="1">
                                On
                                <input @if(isset($datas['data'][$name]['status']) && $datas['data'][$name]['status'] == "2") checked
                                       @endif type="radio" name="data[{!! $name !!}].status"
                                       value="2">
                                Off
                            </div>
                        </td>
                    </tr>
                </table>

            </div>

        </div>
    </li>
    @endfunction
    <div class="row">
        <div class="col-md-12 routers_wrap">
            <!-- The time line -->
            <form id="saveForm">
                <ul class="timeline">
                    <!-- timeline time label -->
                    <li class="time-label">
                  <span class="bg-red">
                    Frontend
                  </span>

                    </li>
                    @php
                        $routes = collect(\Route::getRoutes())->mapWithKeys(function ($route) {
                         return [
                            $route->getName()=>[
                            'action'=>$route->getAction(),
                            'uri'=> $route->uri(),
                            'name'=> $route->getName(),
                            'default'=>$route->defaults,
                            'methods'=>$route->methods
                            ]];
                         });
                         $keyName = app()->getKey("_alias");
                         $lists = [];
                         $urls = [];

                    @endphp
                    @foreach($routes as $name=>$route)
                        @php  $arr_name =  explode(':',$route['name']);
                        @endphp
                        @continue(empty($route['name']))

                        @if( ("frontend"==$arr_name[0] || "frontend"!=$arr_name[0] && $arr_name[0]!="backend") )
                            @if(in_array('GET',$route['methods']))
                                @php
                                    $middlewares = $route['action']['middleware'];
                                    $uri = isset($datas['data'][$name]['data']['uri']) ?$datas['data'][$name]['data']['uri']:$route['uri'];
                                    $lists[$name] = 1;
                                    $urls[$name] = $name;
                                @endphp
                                @view_item($name,$middlewares,$uri,$layouts,$listsRolePremission,$datas,true,$controllers,$route['action']['controller'])
                            @else
                               @php  $urls[$name] = $name; @endphp
                            @endif
                        @endif
                    @endforeach
                    @if(isset($datas['data']))
                       
                        @foreach($datas['data'] as $name=>$route)

                            @if(!isset($urls[$route['data']['name']]))
                                @php
                                    
                                   $middlewares = json_decode($route['data']['middleware'],true);

                                    $middlewares  = is_array($middlewares)?$middlewares:[];
                                    $controller = "";
                                    if(isset($route['data']['controller'])){
                                        $controller = $route['data']['controller'];
                                    }else if( isset($routes[$name]['action']['controller'])){
                                        $controller = $routes[$name]['action']['controller'];
                                    }
                                @endphp
                                @view_item($name,$middlewares,$route['data']['uri'],$layouts,$listsRolePremission,$datas,false,$controllers,$controller)
                            @endif
                        @endforeach
                    @endif
                    <li>
                        <i class="fa fa-clock-o bg-gray"></i>
                    </li>
                </ul>
            </form>
        </div>
        <!-- /.col -->
    </div>
@endsection
@push('scripts')
    <script>
        function SaveLayout() {
            var saveForm = $("#saveForm").zoe_inputs('get');
            $.ajax({
                method: "Post", data: saveForm, success: function (data) {
                    console.log($("#saveForm").html($(data.views['content']).find('#saveForm ul')));
                }
            });
        }

        function changeUri(_this) {
            var parent = $(_this).parent();
            var input = parent.find('.input');
            var view = parent.find('.view');
            if (input.is(":hidden")) {
                view.find('span').hide();
                input.val(view.find('span').text()).show();
            } else {
                var views = $('.routers .router .view span');
                var span = view.find("span");
                var oke = true;
                var _input = input.find('input');
                var val = _input.val();
                views.each(function () {
                    if ($(this).text() === val && span.attr('data-uri') !== $(this).attr('data-uri')) {
                        oke = false;
                    }
                });
                if (oke) {
                    _input.css('border', '1px solid #dedede');
                    if (span.attr('data-uri') !== val) {
                        _input.attr('name', _input.attr('data-name'));
                    } else {
                        _input.attr('data-name', _input.attr('name'));
                        _input.removeAttr('name');
                    }
                    span.text(val);
                    span.show();
                    input.hide();
                } else {
                    _input.css('border', '1px solid red');
                }
            }
        }

        $(document).ready(function () {
            $("#saveForm").zoe_inputs('set', @json($datas), {'cache': 0}, false)
        });
    </script>
@endpush