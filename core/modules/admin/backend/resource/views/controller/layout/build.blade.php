@section('content-header')
    <h1>
        {!! @z_language(["Manager Layout"]) !!}
    </h1>
@endsection
@section('content')
    @breadcrumb()@endbreadcrumb
    @php
        $obj_layout = new \Admin\Lib\LayoutBlade();

    @endphp
    <div class="box box box-zoe">
        <div class="box-body clearfix">
            <ul class="timeline timeline-inverse builds">
                <li class="time-label">
                    <button action="0" class="btn  btn-default" style="margin-left: 7px;"
                            onclick="BuildAll(this,'build')">Build
                    </button>
                </li>
                @foreach($lists as $list)
                    @php
                        $data_path = $obj_layout->initPath($list->type_group);
                       if($list->type == "partial"){
                           $fileName = $obj_layout->FilenamePartial($list->slug, $list);
                       }else{
                           $fileName = $obj_layout->FilenameLayout($list->slug, $list);
                       }
                       $FileNameBlade = view()->exists("zoe::".$data_path['prefix'].$fileName)?view()->getFinder()->find(\Illuminate\View\ViewName::normalize("zoe::".$data_path['prefix'].$fileName)):"";
                       $boolFilePhp = false;
                       $FileNamePhp= "";
                       $sources= "";
                       if(!empty($FileNameBlade)){
                           $FileNamePhp = config('view.compiled')."/".sha1($FileNameBlade).".php";
                           if(file_exists($FileNamePhp)){
                               $boolFilePhp = true;

                           }
                       }
                    @endphp
                    <li data-id="{!! $list->id !!}">
                        <i class="fa fa-refresh"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-clock-o"></i> {!! $list->updated_at !!}</span>
                            <h3 class="timeline-header"><strong> {!! $list->name !!} </strong>
                                <span class="label label-success">{!! $list->type !!}</span> &nbsp;
                                <span class="label {!! !empty($FileNameBlade)?"label-info":"label-danger" !!}">Blade</span>
                                &nbsp;
                                <span class="label {!! $boolFilePhp?"label-info":"label-danger" !!}">Php</span> &nbsp;
                            </h3>

                            <div class="timeline-body">

                            </div>
                            <div class="timeline-footer">
                                <a href="{!! route('backend:layout:edit',["id"=>$list->id]) !!}"
                                   class="btn btn-default btn-xs">{!! z_language('Edit') !!}</a>&nbsp;
                                <a onclick="Action(this)" class="btn btn-default btn-xs build" data-act="build"
                                   data-id="{!! $list->id !!}">{!! z_language('Build') !!}</a>&nbsp;
                                @if($boolFilePhp)
                                    <a onclick="Action(this)" class="btn btn-default btn-xs delete" data-act="delete"
                                       data-id="{!! $list->id !!}">{!! z_language('Delete') !!}</a> &nbsp;
                                    <a onclick="OpenSource(this)" class="btn btn-default btn-xs view"
                                       data-act="view-php"
                                       data-id="{!! $list->id !!}">{!! z_language('View Php') !!}</a>&nbsp;
                                @endif
                                @if(!empty($FileNameBlade))
                                    <a onclick="OpenSource(this)" class="btn btn-default btn-xs view"
                                       data-act="view-blade"
                                       data-id="{!! $list->id !!}">{!! z_language('View Blade') !!}</a>&nbsp;
                                @endif
                                @if(!empty($FileNameBlade))
                                    <a onclick="OpenSource(this)" class="btn btn-default btn-xs view"
                                       data-act="view-html"
                                       data-id="{!! $list->id !!}">{!! z_language('View Html') !!}</a>&nbsp;
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
                <li>
                    <button action="0" class="btn  btn-default" style="margin-left: 7px;"
                            onclick="BuildAll(this,'delete')">Delete
                    </button>
                </li>
            </ul>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('module/admin/assets/bootpopup/bootpopup.js')}}"></script>
    <link rel="stylesheet"
          href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.10/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.10/highlight.min.js"></script>
    <script>
        function initSnippet(html) {
            let snippet = document.querySelector('#snippet pre code');
            if (html) {
                snippet.innerHTML = html;
            }
            hljs.highlightBlock(snippet);
            $('.source').loading({destroy: true});
        }

        function OpenSource(self) {
            var data = $(self).data();
            $.ajax({
                type: 'POST',
                url: '{{route('backend:layout:ajax:build')}}',
                data: data,
                success: function (json) {
                    bootpopup({
                        title: "Custom HTML",
                        size: "large",
                        content: ['<div id="snippet">\n' +
                        '                                <pre>\n' +
                        '                                    <code class="html">' + json.content + '</code>\n' +
                        '                                </pre>\n' +
                        '                            </div>'],
                        cancel: function (data, array, event) {

                        },
                        before: function (_this) {
                            var snippet = _this.form.find('#snippet');
                            hljs.highlightBlock(snippet[0]);
                        }
                    });
                }
            });
        }

        function Action(self, cb) {
            var data = $(self).data();
            console.log(data);
            $.ajax({
                type: 'POST',
                url: '{{route('backend:layout:ajax:build')}}',
                data: data,
                success: function (data) {
                    if (cb) {
                        cb(data);
                    }
                }
            });
        }

        function BuildAll(self, name) {
            if ($(self).attr('action') === 1) {
                return;
            }

            $(self).attr('disabled', true);
            $arr_Promise = [];
            $(".builds li").each(function () {
                let build = $(this).find('.' + name);
                if (build.length > 0) {
                    let i = $(this).find('i');
                    $arr_Promise.push(new Promise((resolve, reject) => {
                        i.addClass('fa-spin');
                        Action($(build), function (data) {
                            setTimeout(function () {
                                resolve(data);
                                i.removeClass('fa-spin');
                            }, 500 * (Math.random() * (5 - 1) + 1));
                        });
                    }));
                }
            });
            Promise.all($arr_Promise).then(values => {
                console.log(values); // [3, 1337, "foo"]
                $(self).removeAttr('disabled');
            });
        }
    </script>
@endpush