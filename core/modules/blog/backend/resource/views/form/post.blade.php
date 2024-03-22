@if(isset($item))
    {!! Form::model($item, ['method' => 'POST','route' => ['backend:blog:post:store'],'id'=>'form_store']) !!}
    {!! Form::hidden('id') !!}
@else
    {!! Form::open(['method' => 'POST','route' => ['backend:blog:post:store'],'id'=>'form_store']) !!}
@endif

<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<div class="col-md-9">
    <div class="nav-tabs-custom">

        @if(isset($configs['post']['language']['multiple']))
            <ul class="nav nav-tabs" {{$current_language}}>
                @foreach($language as $lang=>$_language)
                    @if(isset($configs['post']['language']['lists']) &&(is_string($configs['post']['language']['lists']) && $configs['post']['language']['lists'] == $_language['lang']|| is_array($configs['post']['language']['lists']) && in_array($_language['lang'],$configs['post']['language']['lists'])))
                        <li {{$lang}} {{$_language['lang'] == $current_language?"class=active":""}}><a href="#tab_{{$lang}}"
                                                                                data-toggle="tab"><span
                                        class="flag-icon flag-icon-{{$_language['flag']}}"></span></a></li>
                    @endif
                @endforeach
            </ul>
            <div class="tab-content">

                @foreach($language as $lang=>$_language)
                    @if(isset($configs['post']['language']['lists']) && (is_string($configs['post']['language']['lists']) && $configs['post']['language']['lists'] == $_language['lang']|| is_array($configs['post']['language']['lists']) &&  in_array($_language['lang'],$configs['post']['language']['lists'])) )
                        <div class="tab-pane {{$_language['lang'] == $current_language?" active":""}}" id="tab_{{$lang}}">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div><br/>
                            @endif
                            <table class="table table-borderless">
                                <tbody>
                                <tr>
                                    <td>
                                        {!! Form::label('id_title', 'Name', ['class' => 'title']) !!}
                                        @if($current_language == $lang)
                                            {!! Form::text('title',null, ['class' => 'form-control','placeholder'=>'Title']) !!}
                                        @else
                                            {!! Form::text('title_'.$lang.'',null, ['class' => 'form-control','placeholder'=>'Title']) !!}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {!! Form::label('id_description', 'Description', ['class' => 'description']) !!}
                                        @if($current_language == $lang)
                                            {!! Form::textarea('description',null, ['class' => 'form-control','placeholder'=>'Description','cols'=>5,'rows'=>5]) !!}
                                        @else
                                            {!! Form::textarea('description_'.$lang.'',null, ['class' => 'form-control','placeholder'=>'Description','cols'=>5,'rows'=>5]) !!}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @if($current_language == $lang)
                                            {!! Form::textarea('content', null, ['class' => 'form-control my-editor']) !!}
                                        @else
                                            {!! Form::textarea('content_'.$lang.'', null, ['class' => 'form-control my-editor']) !!}
                                        @endif
                                        <script>
                                            var editor_config = {
                                                    path_absolute: "/",
                                                    selector: "textarea.my-editor",
                                                    plugins: [
                                                        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                                                        "searchreplace wordcount visualblocks visualchars code fullscreen",
                                                        "insertdatetime media nonbreaking save table contextmenu directionality",
                                                        "emoticons template paste textcolor colorpicker textpattern"
                                                    ],
                                                    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                                                    relative_urls: false,
                                                    file_browser_callback: function (field_name, url, type, win) {

                                                        var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                                                        var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

                                                        var cmsURL = '{{route('backend:elfinder:tinymce4')}}' + '?field_name=' + field_name;
                                                        console.log(cmsURL);
                                                        if (type === 'image') {
                                                            cmsURL = cmsURL + "&type=Images";
                                                        } else {
                                                            cmsURL = cmsURL + "&type=Files";
                                                        }
                                                        tinyMCE.activeEditor.windowManager.open({
                                                            file: cmsURL,
                                                            title: 'Filemanager',
                                                            width: x * 0.8,
                                                            height: y * 0.8,
                                                            resizable: "yes",
                                                            close_previous: "no",
                                                        }, {
                                                            oninsert: function (file, fm) {
                                                                var url, reg, info;
                                                                console.log(file);
                                                                win.document.getElementById(field_name).value = file.url;
                                                                // URL normalization
                                                                url = fm.convAbsUrl(file.url);
                                                                console.log(url);
                                                            }
                                                        });

                                                    }
                                                }
                                            ;

                                            tinymce.init(editor_config);
                                        </script>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div><br/>
            @endif
            <table class="table table-borderless">
                <tbody>
                <tr>
                    <td>
                        {!! Form::label('id_title', 'Name', ['class' => 'title']) !!}
                        {!! Form::text('title',null, ['class' => 'form-control','placeholder'=>'Title']) !!}
                    </td>
                </tr>
                <tr>
                    <td>
                        {!! Form::label('id_description', 'Description', ['class' => 'description']) !!}
                        {!! Form::textarea('description',null, ['class' => 'form-control','placeholder'=>'Description','cols'=>5,'rows'=>5]) !!}
                    </td>
                </tr>
                <tr>
                    <td>
                        {!! Form::textarea('content', null, ['class' => 'form-control my-editor']) !!}
                        <script>
                            var editor_config = {
                                    path_absolute: "/",
                                    selector: "textarea.my-editor",
                                    plugins: [
                                        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                                        "searchreplace wordcount visualblocks visualchars code fullscreen",
                                        "insertdatetime media nonbreaking save table contextmenu directionality",
                                        "emoticons template paste textcolor colorpicker textpattern"
                                    ],
                                    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                                    relative_urls: false,
                                    file_browser_callback: function (field_name, url, type, win) {

                                        var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                                        var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

                                        var cmsURL = '{{route('backend:elfinder:tinymce4')}}' + '?field_name=' + field_name;
                                        console.log(cmsURL);
                                        if (type === 'image') {
                                            cmsURL = cmsURL + "&type=Images";
                                        } else {
                                            cmsURL = cmsURL + "&type=Files";
                                        }
                                        tinyMCE.activeEditor.windowManager.open({
                                            file: cmsURL,
                                            title: 'Filemanager',
                                            width: x * 0.8,
                                            height: y * 0.8,
                                            resizable: "yes",
                                            close_previous: "no",
                                        }, {
                                            oninsert: function (file, fm) {
                                                var url, reg, info;
                                                console.log(file);
                                                win.document.getElementById(field_name).value = file.url;
                                                // URL normalization
                                                url = fm.convAbsUrl(file.url);
                                                console.log(url);
                                            }
                                        });

                                    }
                                }
                            ;

                            tinymce.init(editor_config);
                        </script>
                    </td>
                </tr>
                </tbody>
            </table>
        @endif
    </div>
</div>
<div class="col-md-3">
    <div class="box box box-zoe">
        <div class="box-body">

            {!! Form::label('id_tag', 'Tag', ['class' => 'tag']) !!} *
            {!! Form::text('tag',null, ['class' => 'form-control','placeholder'=>'Tag']) !!}
        </div>
    </div>
    <div class="box box box-zoe">
        <div class="box-body">

            {!! Form::label('id_tag', 'Category', ['class' => 'Category']) !!} *
            {!! Form::CategoriesNestable($nestables,$item?$item->category:[],"category") !!}

        </div>
    </div>
    <div class="box box box-zoe">
        <div class="box-body">
            {!! Form::label('id_status', 'Status', ['class' => 'status']) !!}
            {!! Form::radio('status', '1' , true) !!} Yes
            {!! Form::radio('status', '0',false) !!} No
        </div>
    </div>
    <div class="box box box-zoe">
        <div class="box-body">
            {!! Form::label('id_status', 'Featured', ['class' => 'status']) !!}
            {!! Form::radio('featured', '1' , true) !!} Yes
            {!! Form::radio('featured', '0',false) !!} No
        </div>
    </div>

    <div class="box box box-zoe">
        <div class="box-body">
            {!! Form::label('id_status', 'Image', ['class' => 'status']) !!}
            <div class="image-wrapper">
                <div class="preview-image-wrapper">
                    <img src="{{$item?$item->image:'http://placehold.jp/150x150.png'}}" alt="" height="150px">
                    <a onclick="btn_remove_image(this)" class="btn_remove_image" title="Remove image">
                        <i class="fa fa-times"></i>
                    </a>
                    {!! Form::hidden('image',null, []) !!}
                </div>
                <a href="#" onclick="openElfinder(this);" class="btn_gallery">
                    Choose image
                </a>

            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}
<div class="modal fade" id="elfinderShow">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="elfinder"></div>
            </div>
        </div>
    </div>
</div>
@section('extra-script')
    <script type="text/javascript">

    </script>
@endsection
@push('links')
    <link rel="stylesheet" href="{{asset("module/admin/assets/flag/css/flag-icon.min.css")}}">
@endpush
@push('scripts')
    <style>
        .preview-image-wrapper {
            position: relative;
        }

        .btn_remove_image {
            position: absolute;
            top: 10px;
            width: 50px;
            font-size: 15px;
        }
    </style>

    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css"/>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('module/admin/assets/elfinder/css/elfinder.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('module/admin/assets/elfinder/css/theme.css') }}">
    <script src="{{ asset('module/admin/assets/elfinder/js/elfinder.min.js') }}"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('module/admin/assets/boostrap-multi-select/css/bootstrap-multiselect.css') }}">
    <script src="{{ asset('module/admin/assets/boostrap-multi-select/js/bootstrap-multiselect.js') }}"></script>


    <link rel="stylesheet" type="text/css" href="{{ asset('module/admin/assets/tagging/css/amsify.suggestags.css') }}">
    <script src="{{ asset('module/admin/assets/tagging/js/jquery.amsify.suggestags.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            // $('#category-select').multiselect();
            var tags = [];
            $('input[name="tag"]').amsifySuggestags({
                type: 'bootstrap',
                suggestions: ['Black', 'White', 'Red', 'Blue', 'Green', 'Orange'],
                afterAdd: function (value) {
                    console.log()
                },
                afterRemove: function (value) {
                    // after remove
                },

            });

        });

        function btn_remove_image(self) {
            console.log($(self).closest('.preview-image-wrapper').find('img').attr('src', 'http://placehold.jp/150x150.png'));
        }

        function openElfinder(self) {
            $('#elfinderShow').modal();
            $('#elfinder').elfinder({
                debug: false,
                width: '100%',
                height: '80%',
                cssAutoLoad: false,
                customData: {
                    _token: '{{ csrf_token() }}'
                },
                commandsOptions: {
                    getfile: {
                        onlyPath: true,
                        folders: false,
                        multiple: false,
                        oncomplete: 'destroy'
                    },
                    ui: 'uploadbutton'
                },
                mimeDetect: 'internal',
                onlyMimes: [
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                    'image/gif'
                ],
                ui: ['toolbar', 'path', 'stat'],
                rememberLastDir: false,
                url: '{{ route("backend:elfinder:showConnector") }}?image=1',
                soundPath: '{{ asset('module/admin/assets/elfinder/sounds') }}',
                getFileCallback: function (file) {
                    console.log(file);

                    var preview_image_wrapper = $(".preview-image-wrapper");
                    preview_image_wrapper.show();
                    console.log(file.url);

                    preview_image_wrapper.find("img").attr('src', file.url);
                    preview_image_wrapper.find("[name='image']").val(file.url);

                    $('#elfinderShow').modal('hide');
                },
                resizable: false,
                uiOptions: {
                    toolbar: [
                        ['home', 'up'],
                        ['upload'],
                        ['quicklook'],
                    ],
                    tree: {
                        openRootOnLoad: true,
                        syncTree: true
                    },
                    navbar: {
                        minWidth: 150,
                        maxWidth: 500
                    },
                    cwd: {
                        oldSchool: false
                    }
                }
            }).elfinder('instance');
        }
    </script>
@endpush
