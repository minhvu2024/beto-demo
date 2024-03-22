@section('content-header')
    <h1>
        {!! @z_language(["Manager Configuration"]) !!}
        {{--<small>it all starts here</small>--}}
        <button type="button" onclick="Save()" class="btn btn-default btn-md"> {!! @z_language(["Save"]) !!} </button>
    </h1>
@endsection
@section('content')
    <div id="configWrap" class="nav-tabs-custom clearfix">
        @if($active == 'system')
            <ul class="nav nav-tabs">
                @foreach($lists as $key=>$list)
                    @if(is_array($list['view']) || isset($list['view']) && view()->exists($list['view']))
                        <li @if($key == $active) class="active" @endif>
                            <a href="#tab_{!! $key !!}" data-toggle="tab">
                                <strong>{!! z_language($list['label']) !!}</strong>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        @else
          <h3>
             <div> <strong>{!! z_language($lists[$active]['label']) !!}</strong></div>
          </h3>
        @endif
        <div class="tab-content clearfix">
            @foreach($lists as $key=>$list)
                @if( isset($list['view']) &&( is_array($list['view']) ||  view()->exists($list['view']) ))
                    @php
                        $_config = config_get('config',$key);
                    @endphp
                    <div class="tab-pane @if($key == $active) active @endif clearfix" id="tab_{!! $key !!}">
                        <form action="" data-key="{!! $key !!}">
                        @if(is_array($list['view']))
                            <!-- tabs -->
                                <table class="table table-bordered">
                                    <tr>
                                        <td class="">
                                            <div class="vertical-tab" role="tabpanel" style="width: 100%">
                                                <!-- Nav tabs -->
                                                <ul class="nav nav-tabs" role="tablist">
                                                    @php $iactive = "post" @endphp
                                                    @foreach($list['view'] as $_key=>$view)
                                                        <li role="presentation"
                                                            @if($_key == $iactive) class="active" @endif>
                                                            <a href="#tab_{!! $key !!}_{!! $_key !!}"
                                                               aria-controls="home"
                                                               role="tab"
                                                               data-toggle="tab">{!! $view['label'] !!}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <!-- Tab panes -->
                                                <div class="tab-content tabs">
                                                    @foreach($list['view'] as $_key=>$view)
                                                        @php
                                                            $keyName = isset($view['name'])?$view['name']:$key;

                                                        @endphp
                                                        <div role="tabpanel"
                                                             class="tab-pane fade in @if($_key == $iactive) active @endif"
                                                             id="tab_{!! $key !!}_{!! $_key !!}">
                                                            @include($view['view'],['keyName'=>empty($keyName)?"":$keyName.".",'config'=>empty($keyName) || !isset($_config[$keyName])?$_config:$_config[$keyName],'key'=>$key."_".$_key,'option'=>$view])
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            @else
                                @php
                                    $keyName = isset($list['name'])?$list['name']:$key;

                                @endphp
                                @include($list['view'],['keyName'=>empty($keyName)?"":$keyName.".",'config'=>empty($keyName) || !isset($_config[$keyName])?$_config:$_config[$keyName],'key'=>$key,'option'=>$list])
                            @endif
                        </form>
                    </div>
                    @push('scripts')
                        <script>
                            $("#tab_{!! $key !!} form").zoe_inputs('set', @json($_config));
                        </script>
                    @endpush
                @endif
            @endforeach
        </div>
        <!-- /.tab-content -->
    </div>
    <style>
        #configWrap .table tr td div {
            padding: 0;
        }

        .tabs-left > .nav-tabs {
            border-bottom: 0;
        }

        .tab-content > .tab-pane,
        .pill-content > .pill-pane {
            display: none;
        }

        .tab-content > .active,
        .pill-content > .active {
            display: block;
        }

        .tabs-left > .nav-tabs > li {
            float: none;
        }

        .tabs-left > .nav-tabs > li > a {
            min-width: 74px;
            margin-right: 0;
            margin-bottom: 3px;
        }

        .tabs-left > .nav-tabs {
            float: left;
            margin-right: 19px;
            border-right: 1px solid #ddd;
        }

        .tabs-left > .nav-tabs > li > a {
            margin-right: -1px;
            -webkit-border-radius: 4px 0 0 4px;
            -moz-border-radius: 4px 0 0 4px;
            border-radius: 4px 0 0 4px;
        }

        .tabs-left > .nav-tabs > li > a:hover,
        .tabs-left > .nav-tabs > li > a:focus {
            border-color: #eeeeee #dddddd #eeeeee #eeeeee;
        }

        .tabs-left > .nav-tabs .active > a,
        .tabs-left > .nav-tabs .active > a:hover,
        .tabs-left > .nav-tabs .active > a:focus {
            border-color: #ddd transparent #ddd #ddd;
            *border-right-color: #ffffff;
        }

        a:hover, a:focus {
            text-decoration: none;
            outline: none;
        }

        .vertical-tab {
            font-family: 'Titillium Web', sans-serif;
            display: table;
        }

        .vertical-tab .nav-tabs {
            display: table-cell;
            width: 15%;
            min-width: 15%;
            vertical-align: top;
            border: none;
        }

        .vertical-tab .nav-tabs li {
            float: none;
            vertical-align: top;
        }

        .vertical-tab .nav-tabs li a {
            color: #555;
            background: #fff;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            text-transform: uppercase;
            padding: 10px 15px;
            margin: 0 10px 5px 0;
            border-radius: 0;
            border: 1px solid #c9cba3;
            position: relative;
            transition: all 0.5s ease 0s;
        }

        .vertical-tab .nav-tabs li a:hover,
        .vertical-tab .nav-tabs li.active a,
        .vertical-tab .nav-tabs li.active a:hover {
            color: #444;
            border-color: #0073aa;
        }

        .vertical-tab .nav-tabs li a:before,
        .vertical-tab .nav-tabs li a:after {
            content: "";
            background: #c9cba3;
            height: 100%;
            width: 10px;
            position: absolute;
            top: 0;
            left: 0;
            transition: all 0.3s ease 0s;
        }

        .vertical-tab .nav-tabs li a:after {
            background: #555;
        }

        .vertical-tab .nav-tabs li a:hover:before,
        .vertical-tab .nav-tabs li.active a:before {
            background: #0073aa;
        }

        .vertical-tab .nav-tabs li a:hover:after,
        .vertical-tab .nav-tabs li.active a:after {
            width: 100%;
            opacity: 0;
        }

        .vertical-tab .tab-content {
            color: #777;
            font-size: 14px;
            line-height: 26px;
            padding: 0 10px;
        }

        .vertical-tab .tab-content h3 {
            font-weight: 600;
            text-transform: uppercase;
            margin: 0 0 5px 0;
        }

        @media only screen and (max-width: 479px) {
            .vertical-tab .nav-tabs {
                width: 100%;
                display: block;
            }

            .vertical-tab .nav-tabs li a {
                padding: 7px 7px;
                margin: 0 0 10px 0;
            }

            .vertical-tab .tab-content {
                padding: 20px 15px 10px;
                display: block;
            }

            .vertical-tab .tab-content h3 {
                font-size: 18px;
            }
        }
    </style>
@endsection
@push('scripts')
    <script>

        function Save() {

            let oke =  confirm('{!! z_language('Bạn muốn lưu') !!}');

            if(oke){
                var form = $("#configWrap .active form");
                var data = form.zoe_inputs('get');
                console.log(data);
                form.loading({circles: 3, overlay: true, width: "5em", top: "35%", left: "50%"});
                $.ajax({
                    type: 'POST',
                    url: '{!! route('backend:configuration:ajax') !!}',
                    data: {
                        data: data,
                        key: form.data('key')
                    },
                    success: function (data) {
                        console.log(data);
                        form.loading({destroy: true});
                    }
                });
            }

        }
    </script>
@endpush

