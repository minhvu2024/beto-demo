@php $option = app()->getConfig()->options; @endphp
@if(isset($name) && isset($option[$name]))
    @php
        $data = $option[$name];
        $_data = config_get('option',$name);
        $data['data'] = isset($data['data'])?array_merge($data['data'],$_data):$_data;
        $parameter = isset($parameter)?$parameter:[];
    @endphp

    @isset($data['config']['columns'])
        @if(isset($tool))
            <div class="box box box-zoe">
                <div class="box-body clearfix">
                    <form action="" id="filter_form">
                        {!! $tool !!}
                    </form>
                </div>
            </div>
        @endif
        <div class="box box box-zoe" id="sectionList">
            <div class="box-header with-border">
                <div class="box-tools">
                    <form method="GET" id="filter_search_form">
                        <div class="input-group input-group-sm hidden-xs" style="width: 250px;">
                            <input type="text" name="filter.search" class="form-control pull-right"
                                   value="{{old('search')}}"
                                   placeholder="{!! z_language("Tìm kiếm") !!}">
                            <div class="input-group-btn">
                                <button type="button" id="BtnSearch" class="btn btn-default"><i
                                            class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                {{--<div style="padding: 5px">--}}
                    {{--<a href="{!! url()->current() !!}">{{ z_language('Tất cả') }}</a> | <a href="?status=1">{{ z_language('Bật') }}</a> | <a href="?status=0">{{ z_language('Tắt') }}</a>--}}
                {{--</div>--}}
            </div>
            <div class="box-body listMain">
                    <table class="table table-bordered">
                    <thead>
                    <tr>
                        @php  $model =(!is_null($models) && count($models)>0)?$models[0]:null;  @endphp
                        @foreach($data['config']['columns']['lists'] as $k=>$columns)
                            @isset($data['data']['columns'][$k])
                                @continue(isset($route[$k]))
                                @if($model!=null && property_exists($model,$k) || (isset($columns['callback']) && isset($callback[$columns['callback']])) || $k =="actions" )
                                @if('id'== $columns['type'])
                                    <th class="column @isset($columns['primary']) column-primary @endisset column-{!! $columns['type'] !!} @isset($columns['order_by']) column-order_by @endisset {{list_text_aligin($columns)}}">
                                        {{z_language($columns['label'])}}
                                        {!! sort_type(isset($columns['order_by'])?$columns['order_by']:"",$k,$parameter) !!}
                                    </th>
                                @else
                                    <th class="column @isset($columns['primary']) column-primary @endisset column-{!! $columns['type'] !!} @isset($columns['order_by']) column-order_by @endisset {{list_text_aligin($columns)}}">
                                        {{z_language($columns['label'])}}
                                        @isset($columns['order_by'])
                                            {!! sort_type(isset($columns['order_by'])?$columns['order_by']:"",$k,$parameter) !!}
                                        @endisset
                                    </th>
                                @endif
                                @endif
                            @endisset
                        @endforeach
                    </tr>
                     </thead>
                    <tbody>

                    @if(count($models)>0)
                        @foreach ($models as $k=>$model)
                            <tr class="list-row">

                                @foreach($data['config']['columns']['lists'] as $key=>$columns)
                                    @isset($data['data']['columns'][$key])

                                        @continue(isset($route[$key]))
                                        @if(!property_exists($model,$key) && isset($columns['callback']) && isset($callback[$columns['callback']]))
                                            @php
                                              $model->{$key} = call_user_func_array($callback[$columns['callback']],[$model]);
                                            @endphp
                                        @endif
                                        @if('title'== $columns['type'])
                                            <td scope="col"
                                                class="column column-primary column-name {{list_text_aligin($columns)}}">
                                                <strong><a class="row-title"
                                                           href="#">@php echo list_label($model->{$key},$columns,$data,$model); @endphp</a></strong>
                                                <div class="row-actions text-center">
                                                    @isset($data['config']['pagination']['router'])
                                                        @php  $n = count($data['config']['pagination']['router'])-1; $i=0; @endphp
                                                        @foreach($data['config']['pagination']['router'] as $id=>$router)
                                                            @php
                                                                $par = isset($route)?$route:[];
                                                                foreach ($router['par'] as $k=>$v){
                                                                    $par[$k] = $model->{$v};
                                                                }
                                                                $key_form = md5(rand(1,10000) . rand(1,10000));
                                                            @endphp
                                                            <span class="{{$id}}">
                                                     @isset($router['method'])
                                                                    <form id="{{$id}}-form-{{$key_form}}"
                                                                          action="{{route($router['name'],$par)}}"
                                                                          method="{{$router['method']}}"
                                                                          style="display: none;">
                                                                        <input name="ref" type="hidden" value="{!! url()->current(); !!}">
                                                        @csrf
                                                    </form>
                                                                    <a  href="#"
                                                                       onclick="event.preventDefault();if(confirm('{!! z_language('Bạn muốn xóa bảng ghi này') !!}')){ document.getElementById('{{$id}}-form-{{$key_form}}').submit();}"> {{$router['label']}} </a> {{$i++<$n?"|":""}}
                                                                @else

                                                                    <a {!! isset($router['hide'])?'style="display:none"':"" !!} href="{{route($router['name'],$par)}}"> {{$router['label']}} </a> {{($i++<$n)?"  | ":""}}
                                                                @endif
                                                </span>
                                                        @endforeach
                                                    @endisset
                                                </div>
                                                <div class="tool">
                                                    <button type="button" class="btn btn-box-tool">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        @elseif($columns['type'] == 'id')

                                            <td class="column @isset($columns['primary']) column-primary @endisset column-{!! $columns['type'] !!}" @php echo attr_row($columns['type'],$data['config']['config']) @endphp>@php echo list_label($model->{$key},$columns,$data,$model); @endphp</td>
                                        @elseif($columns['type'] == 'action')
                                            <td>
                                                 @foreach($columns['lists'] as $lists)
                                                        {!! render_attr($lists,$model)  !!}
                                                 @endforeach
                                            </td>
                                        @else
                                            <td data="col"
                                                class="column @isset($columns['primary']) column-primary @endisset {{list_text_aligin($columns)}}" @php echo attr_row($columns['type'],$data['config']['config']) @endphp>@php echo list_label($model->{$key},$columns,$data,$model); @endphp</td>
                                        @endif
                                    @endisset
                                @endforeach
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="@php echo count($data['config']['columns']['lists'])+2 @endphp">
                                <strong>{{z_language('Danh sách trống')}}</strong></td>
                        </tr>
                    @endif
                        <tfooter>
                           <tr>
                               <td>
                                   @if(isset($main))
                                       {!! $main !!}
                                   @endif

                               </td>
                           </tr>
                        </tfooter>
                    </tbody>

                </table>

            </div>
            <div class="box-footer clearfix">
                {{ $models->links('backend::layout.pagination.pagination', []) }}
            </div>
        </div>
    @endisset
@endif
@push('scripts')
    <script>
        $(document).ready(function () {

            $("#sectionList .listMain").on('click', '.column-order_by', function () {
                $("#sectionList").loading({circles: 3, overlay: true, width: "5em", top: "30%", left: "50%"});
                var data = $("#sectionList .pagination .active").data();
                var getData = $("#filter_form").zoe_inputs('get');

                getData.action = true;
                getData.order_by = $(this).children('.fa').data();

                $.ajax({
                    type: "GET",
                    url: null,
                    data: getData,
                    success: function (data) {
                        renderContent(data);
                        $("#sectionList").loading({destroy: true});
                    },
                    error: function (xhr, error) {
                        if (xhr.status === 401) {
                            location.reload();
                        }
                    }
                });
            });

            function renderContent(data) {
                if (data.hasOwnProperty('views') && data.views.hasOwnProperty('content')) {
                    var htmlContent = $(data.views.content);
                    var listMain = htmlContent.find(".listMain>table");
                    var pagination = htmlContent.find(".pagination ul li");

                    $("#sectionList .listMain").html(listMain);
                    $("#sectionList .pagination ul").html(pagination);
                }
            }

            $(".btnFilter").click(function () {

                var data = $("#sectionList .pagination .active").data();

                $("#sectionList").loading({circles: 3, overlay: true, width: "5em", top: "30%", left: "50%"});
                var getData = $("#filter_form").zoe_inputs('get');
                getData.action = true;
                $.ajax({
                    type: "GET",
                    url: data && data.hasOwnProperty('url') ? data.url : null,
                    data: getData,
                    success: function (data) {
                        renderContent(data);
                        $("#sectionList").loading({destroy: true});
                    },
                    error: function (xhr, error) {
                        if (xhr.status === 401) {
                            location.reload();
                        }
                    }
                });
            });
            $("#BtnSearch").click(function () {
                var data = $("#sectionList .pagination .active").data();
                $("#sectionList").loading({circles: 3, overlay: true, width: "5em", top: "30%", left: "50%"});
                $.ajax({
                    type: "GET",
                    url:data && data.hasOwnProperty('url')? data.url : window.location.href ,
                    data: $("#filter_search_form").zoe_inputs('get'),
                    success: function (data) {
                        renderContent(data);
                        $("#sectionList").loading({destroy: true});
                    },
                    error: function (xhr, error) {
                        if (xhr.status === 401) {
                            location.reload();
                        }
                    }
                });
            });

            $("#sectionList .listMain").on('mouseenter', '.list-row', function () {
                $(this).find('.row-actions').css({display: "block"});
            }).on('mouseleave', '.list-row', function () {
                $(this).find('.row-actions').css({display: "none"});
            }).on('click', ".btn-box-tool", function () {
                var i = $(this).find('i');
                if ($(this).find('i').hasClass('fa-plus')) {
                    i.removeClass('fa-plus');
                    i.addClass('fa-minus');
                    $(this).closest('tr').find('.row-actions').css({display: "block"});
                } else {
                    i.addClass('fa-plus');
                    i.removeClass('fa-minus');
                    $(this).closest('tr').find('.row-actions').css({display: "none"});
                }
            });
            $("#sectionList .pagination ul").on('click', '.link', function (e) {
                e.preventDefault();
                var href = $(this).attr('href');
                $("#sectionList").loading({circles: 3, overlay: true, width: "5em", top: "30%", left: "50%"});
                $.ajax({
                    type: "GET",
                    url: href,
                    data: $("#filter_form").zoe_inputs('get'),
                    success: function (data) {
                        renderContent(data);
                        $("#sectionList").loading({destroy: true});
                    },
                    error: function (xhr, error) {
                        if (xhr.status === 401) {
                            location.reload();
                        }
                        $("#sectionList").loading({destroy: true});
                    }
                });
            });
        });
    </script>
@endpush
