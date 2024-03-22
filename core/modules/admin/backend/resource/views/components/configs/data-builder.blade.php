<div class="table-responsive" id="Data-Builder-Config">

    @if(isset($data["views"]['top']))
        @foreach($data["views"]['top'] as $top)
            @includeIf($top,['data'=>$data,'config'=>$config])
        @endforeach
    @endif



    @if(isset($data['action']['create']) && $data['action']['create'])
        <div style="padding: 10px" class="clearfix">
            <button type="button" class="btn btn-info btn-xs pull-right">Add</button>
        </div>
    @endif
    @function(renderTemplate ($config,$data,$i,$tag,$template))
    @if(isset($data['action']['sort']) && $data['action']['sort'])
        <td class="drags" style="vertical-align: middle;">
            Drag
        </td>
    @endif
    @isset($data["items"])
        @foreach($data["items"] as $name=>$items)
            @continue(!is_array($items))
            <{!! $tag !!}>
            @if($items['type'] == 'route')
                @php
                    $routes = collect(\Route::getRoutes())->map(function ($route) { return ['uri'=> $route->uri(),'name'=> $route->getName()]; });
                @endphp
                <div class="input-group">
                    <select class="selectChange form-control" data-name="opt.lists[%INDEX%].{!! $name !!}"
                            @if($template ==  false) name="opt.lists[{!! $i !!}].{!! $name !!}" @endif>

                        @foreach($routes as $route)
                            @if(isset($data['attrs']['route']))
                                @if($data['attrs']['route'] != "all")
                                    @php $arr_name =  explode(':',$route['name']); @endphp
                                    @continue($data['attrs']['route']!=$arr_name[0])
                                @endif
                            @endif
                            <option value="{!! $route['name'] !!}" data-uri="{!! $route['uri'] !!}">
                                @php
                                    if(isset($config['opt']['lists'][$i][$name]) && $config['opt']['lists'][$i][$name] == $route['name']){
                                        $_json = json_decode(isset($config['opt']['param'][$i][$name])?$config['opt']['param'][$i][$name]:"{}",true);
                                        $_search = [];
                                        $_val = [];
                                        foreach ($_json as $k=>$_v) {
                                            $_search[] = '{'.$k.'}';
                                            $_val[] = $_v;
                                        }
                                        echo str_replace($_search,$_val, $route['uri']);
                                    }else{
                                        echo $route['uri'];
                                    }
                                @endphp
                            </option>

                        @endforeach

                    </select>
                    <span class="input-group-addon">
                                     <button type="button" class="btn btn-info btn-xs pull-right"
                                             onclick="ChangePar(this);">Param
                                    </button>
                                </span>
                    <input type="hidden" class="val" @if($template == false) name="opt.param[{!! $i !!}][{!! $name !!}]"
                           @endif
                           data-name="opt.param[%INDEX%][{!! $name !!}]" value="{}">
                </div>
            @elseif($items['type'] == 'category' && isset($items['keyname']))
                @php
                    get_category_type($items['keyname']);
                @endphp
            @elseif($items['type'] == 'select' && isset($items['select']))
                <select class="selectChange form-control" data-name="opt.lists[%INDEX%].{!! $name !!}"
                        @if($template == false) name="opt.lists[{!! $i !!}].{!! $name !!}" @endif>
                    @foreach($items['select'] as $k=>$v)
                        <option value="{!! $k !!}">{!! $v !!}</option>
                    @endforeach
                </select>
            @elseif($items['type'] == 'textarea')
                <textarea class="form-control" @if($template == false) name="opt.lists[{!! $i !!}].{!! $name !!}" @endif
                data-name="opt.lists[%INDEX%].{!! $name !!}"></textarea>
            @elseif($items['type'] == 'img')
                <div class="input-group">
                    <input @if($template == false) name="opt.lists[{!! $i !!}].{!! $name !!}" @endif
                    type="text"
                           data-name="opt.lists[%INDEX%].{!! $name !!}"
                           class="form-control"
                           placeholder="{!! $items['label'] !!}">
                    <span class="input-group-addon">
                                     <button type="button" class="btn btn-info btn-xs pull-right"
                                             onclick="ImageConfig(this);">Param
                                    </button>
                                </span>
                    <input type="hidden" class="val" @if($template == false) name="opt.param[{!! $i !!}][{!! $name !!}]"
                           @endif
                           data-name="opt.param[%INDEX%][{!! $name !!}]" value="{}">
                </div>
            @else
                <input @if($template == false) name="opt.lists[{!! $i !!}].{!! $name !!}" @endif
                type="text"
                       data-name="opt.lists[%INDEX%].{!! $name !!}"
                       class="form-control"
                       placeholder="{!! $items['label'] !!}">
            @endif
</{!! $tag !!}>
@endforeach
@endisset
@if(isset($data['action']['delete']) && $data['action']['delete'])
    <td style="vertical-align: middle;">
        <button type="button" class="btn btn-info btn-xs pull-right" onclick="deleteEle(this);">Delete
        </button>
    </td>
@endif
@endfunction
@isset($data["count"])
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered sortable">
                <tbody>
                @for($i=0;$i<$data["count"];$i++)
                    <tr class="@if($data['action']['sort']) sort @endif">
                        @section('View_'.$i)
                            @renderTemplate($config,$data,$i,"td",false)
                        @endsection
                        @yield('View_'.$i)
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>
    </div>
@endisset
@if(isset($data["views"]['bottom']))
    @foreach($data["views"]['bottom'] as $bottom)
        @includeIf($bottom);
    @endforeach
@endif
@section('View_Template')
    <table class="table table-bordered">
        <tbody>
        <tr>
            @renderTemplate($config,$data,$i,"td",true)
        </tr>
        </tbody>
    </table>
@endsection
<div style="display: none">
    @yield("View_Template")
</div>
<style>
    .round {
        border-bottom-right-radius: 15px;
        border-bottom-left-radius: 15px;
        -moz-border-radius-bottomright: 15px;
        -moz-border-radius-bottomleft: 15px;
        -webkit-border-bottom-right-radius: 15px;
        -webkit-border-bottom-left-radius: 15px;

        -moz-border-radius-topright: 15px;
        -moz-border-radius-topleft: 15px;
        -webkit-border-top-right-radius: 15px;
        -webkit-border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        border-top-left-radius: 15px;
    }
</style>
<script>


    function ResetPos() {
        $("#Data-Builder-Config .sortable tbody > tr").each(function (i, d) {
            $(this).find('[data-name]').each(function () {
                var newAttr = $(this).attr('data-name').replace('%INDEX%', i);
                $(this).attr('name', newAttr);
            });
        });
    }

    function deleteEle(self) {
        var tr = $(self).closest('tr');
        console.log(tr);
        tr.remove();
        ResetPos();
    }

    function ImageConfig(self) {

    }

    function ChangePar(self) {
        var parent = $(self).closest("td");
        var valPar = parent.find(".selectChange :selected").attr('data-uri');

        var par = {};
        var index = 0;
        //for (var i in arr) {
        var open = 0;
        for (var v of valPar) {
            if (v === "{") {
                open = 1;
                index++;
                par[index] = "";
            } else if (v === "}") {
                open = 2;
                index++;
            } else {
                if (open === 1) {
                    par[index] += v;
                }
            }
        }
        if (index > 0) {
            var opt = {
                title: "Add image",
                size: "large",
                showclose: false,
                size_labels: "col-sm-2",
                size_inputs: "col-sm-10",
                content: [],
                before: function (window) {
                    var val = parent.find('.val').val();
                    try {
                        val = JSON.parse(val);
                    } catch (e) {
                        val = {};
                    }
                    $(window.content).find('form').zoe_inputs('set', val);
                },
                dismiss: function (event) {
                    console.log("Dismiss");
                },
                cancel: function (data, array, event) {
                    console.log("Cancel");
                },
                ok: function (data, array, event) {
                    console.log("OK\n" + JSON.stringify(data));
                    parent.find('.val').val(JSON.stringify(data));
                    console.log(valPar);
                    for (var k in data) {
                        valPar = valPar.replace(new RegExp('{' + k + '}', 'g'), data[k]);
                    }
                    console.log(valPar);
                    parent.find(".selectChange :selected").html(valPar);
                },
                complete: function () {
                    console.log("Complete");
                },
            };
            for (var i in par) {
                opt.content.push({
                    input: {
                        type: "text",
                        label: par[i],
                        name: par[i],
                        placeholder: par[i],
                        value: ""
                    }
                });
            }
            bootpopup(opt);
        }
    }

    $(document).ready(function () {

        $("#Data-Builder-Config .sortable tbody").sortable({
            handle: ".drags",
            stop: function (ui, dom) {
                ResetPos();
            }
        });
    })
</script>
</div>
